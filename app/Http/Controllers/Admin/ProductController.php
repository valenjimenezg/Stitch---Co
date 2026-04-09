<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPresentation;
use App\Models\Category;
use App\Models\Producto;
use App\Models\DetalleProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function index()
    {
        $variantes = DetalleProducto::with('producto')
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('variantes'));
    }

    public function export()
    {
        $variantes = DetalleProducto::with('producto')->get();
        $filename = "inventario_stitch_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Producto', 'Categoria', 'Grosor', 'Color', 'Marca', 'CM', 'Precio USD', 'Precio BS', 'Oferta', 'Descuento %', 'Stock'];

        $callback = function() use($variantes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($variantes as $v) {
                fputcsv($file, [
                    $v->id,
                    $v->producto->nombre ?? '',
                    $v->producto->categoria ?? '',
                    $v->grosor,
                    $v->color,
                    $v->marca,
                    $v->cm,
                    $v->precio_usd,
                    $v->precio,
                    $v->en_oferta ? 'Sí' : 'No',
                    $v->descuento_porcentaje,
                    $v->stock
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function restockReport()
    {
        $agotados = DetalleProducto::with('producto')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();
            
        return view('admin.products.restock-report', compact('agotados'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('admin.products.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id'          => 'nullable|exists:productos,id',
            'nombre'               => 'required_without:producto_id|nullable|string|max:150',
            'descripcion'          => 'nullable|string',
            'categoria'            => 'required_without:producto_id|nullable|string|max:100',
            'grosor'               => 'nullable|string|max:50',
            'color'                => 'nullable|string|max:50',
            'marca'                => 'nullable|string|max:100',
            'cm'                   => 'nullable|numeric|min:0',
            'unidad_medida'        => 'nullable|string|max:50',
            'precio_usd'           => 'required|numeric|min:0',
            'en_oferta'            => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock'                => 'required|integer|min:0',
            'imagen'               => 'nullable|image|max:2048',
        ]);

        $bcvRate = \Illuminate\Support\Facades\Cache::get('bcv_rate', 1);
        $bcvRate = max($bcvRate, 1);

        DB::transaction(function () use ($request, $bcvRate) {
            if ($request->filled('producto_id')) {
                $producto = Producto::findOrFail($request->producto_id);
            } else {
                $producto = Producto::create([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'categoria' => $request->categoria,
                ]);
            }

            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('img/productos'), $filename);
                $imagenPath = 'img/productos/' . $filename;
            }

            DetalleProducto::create([
                'producto_id'          => $producto->id,
                'grosor'               => $request->grosor,
                'color'                => $request->color,
                'marca'                => $request->marca,
                'cm'                   => $request->cm,
                'unidad_medida'        => $request->unidad_medida ?? 'Ninguna',
                'precio_usd'           => $request->precio_usd,
                'precio'               => round($request->precio_usd * $bcvRate, 2),
                'stock'                => $request->stock,
                'en_oferta'            => $request->boolean('en_oferta'),
                'descuento_porcentaje' => $request->descuento_porcentaje ?? 0,
                'imagen'               => $imagenPath,
            ]);
        });

        return redirect()->route('admin.products.index')->with('success', 'Producto y variante creados correctamente.');
    }

    public function edit(int $id)
    {
        $variante  = DetalleProducto::with('producto')->findOrFail($id);
        $productos = Producto::orderBy('nombre')->get();
        
        $colores = DetalleProducto::whereNotNull('color')->distinct()->pluck('color')->toJson();
        $grosores = DetalleProducto::whereNotNull('grosor')->distinct()->pluck('grosor')->toJson();
        $marcas = DetalleProducto::whereNotNull('marca')->distinct()->pluck('marca')->toJson();

        return view('admin.products.edit', compact('variante', 'productos', 'colores', 'grosores', 'marcas'));
    }

    public function update(Request $request, int $id)
    {
        $variante = DetalleProducto::findOrFail($id);

        $request->validate([
            'grosor'               => 'nullable|string|max:50',
            'color'                => 'nullable|string|max:50',
            'marca'                => 'nullable|string|max:100',
            'cm'                   => 'nullable|numeric|min:0',
            'unidad_medida'        => 'nullable|string|max:50',
            'precio'               => 'required|numeric|min:0',
            'en_oferta'            => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock'                => 'required|numeric|min:0',
            'imagen'               => 'nullable|image|max:2048',
        ]);

        $bcvRate = \Illuminate\Support\Facades\Cache::get('bcv_rate', 1);
        $bcvRate = max($bcvRate, 1);

        $data = $request->only('grosor', 'color', 'marca', 'cm', 'unidad_medida', 'precio', 'stock');
        $data['precio_usd']           = round($request->precio / $bcvRate, 2);
        $data['en_oferta']            = $request->boolean('en_oferta');
        $data['descuento_porcentaje'] = $request->descuento_porcentaje ?? 0;

        if ($request->hasFile('imagen')) {
            // Delete old
            if ($variante->imagen && file_exists(public_path($variante->imagen))) {
                unlink(public_path($variante->imagen));
            } elseif ($variante->imagen && Storage::disk('public')->exists($variante->imagen)) {
                Storage::disk('public')->delete($variante->imagen);
            }
            
            // Upload new
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/productos'), $filename);
            $data['imagen'] = 'img/productos/' . $filename;
        }

        $variante->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Variante actualizada.');
    }

    public function destroy(int $id)
    {
        $variante = DetalleProducto::findOrFail($id);
        
        if ($variante->imagen && file_exists(public_path($variante->imagen))) {
            unlink(public_path($variante->imagen));
        } elseif ($variante->imagen && Storage::disk('public')->exists($variante->imagen)) {
            Storage::disk('public')->delete($variante->imagen);
        }
        
        $variante->delete();
        return back()->with('success', 'Variante eliminada.');
    }
}
