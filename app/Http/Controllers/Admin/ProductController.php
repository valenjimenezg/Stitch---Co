<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetalleProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'producto_id'         => 'nullable|exists:productos,id',
            'nombre'              => 'required_without:producto_id|string|max:150',
            'descripcion'         => 'nullable|string',
            'categoria'           => 'required|string|max:100',
            'grosor'              => 'nullable|string|max:50',
            'color'               => 'nullable|string|max:50',
            'marca'               => 'nullable|string|max:100',
            'cm'                  => 'nullable|numeric|min:0',
            'precio_usd'          => 'required|numeric|min:0',
            'en_oferta'           => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock'               => 'required|integer|min:0',
            'imagen'              => 'nullable|image|max:2048',
        ]);

        if (!$request->filled('producto_id')) {
            $producto = Producto::create([
                'nombre'      => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria'   => $request->categoria,
            ]);
            $productoId = $producto->id;
        } else {
            $productoId = $request->producto_id;
        }

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
        }

        $bcvRate = \Illuminate\Support\Facades\Cache::get('bcv_rate', 1);
        $precio = round($request->precio_usd * $bcvRate, 2);

        DetalleProducto::create([
            'producto_id'          => $productoId,
            'grosor'               => $request->grosor,
            'color'                => $request->color,
            'marca'                => $request->marca,
            'cm'                   => $request->cm,
            'precio_usd'           => $request->precio_usd,
            'precio'               => $precio,
            'en_oferta'            => $request->boolean('en_oferta'),
            'descuento_porcentaje' => $request->descuento_porcentaje ?? 0,
            'stock'                => $request->stock,
            'imagen'               => $imagenPath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Variante creada correctamente.');
    }

    public function edit(int $id)
    {
        $variante  = DetalleProducto::with('producto')->findOrFail($id);
        $productos = Producto::orderBy('nombre')->get();
        return view('admin.products.edit', compact('variante', 'productos'));
    }

    public function update(Request $request, int $id)
    {
        $variante = DetalleProducto::findOrFail($id);

        $request->validate([
            'grosor'               => 'nullable|string|max:50',
            'color'                => 'nullable|string|max:50',
            'marca'                => 'nullable|string|max:100',
            'cm'                   => 'nullable|numeric|min:0',
            'precio_usd'           => 'required|numeric|min:0',
            'en_oferta'            => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock'                => 'required|integer|min:0',
            'imagen'               => 'nullable|image|max:2048',
        ]);

        $bcvRate = \Illuminate\Support\Facades\Cache::get('bcv_rate', 1);
        $precio = round($request->precio_usd * $bcvRate, 2);

        $data = $request->only('grosor', 'color', 'marca', 'cm', 'precio_usd', 'stock');
        $data['precio'] = $precio;
        $data['en_oferta']            = $request->boolean('en_oferta');
        $data['descuento_porcentaje'] = $request->descuento_porcentaje ?? 0;

        if ($request->hasFile('imagen')) {
            if ($variante->imagen) {
                Storage::disk('public')->delete($variante->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $variante->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Variante actualizada.');
    }

    public function destroy(int $id)
    {
        $variante = DetalleProducto::findOrFail($id);
        if ($variante->imagen) {
            Storage::disk('public')->delete($variante->imagen);
        }
        $variante->delete();
        return back()->with('success', 'Variante eliminada.');
    }
}
