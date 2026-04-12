<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductoVariante;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // Get parent variants directly
        $variantes = ProductoVariante::with('producto')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('variantes'));
    }

    public function export()
    {
        $variantes = ProductoVariante::with('producto')->get();
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
                    $v->producto->categoria->nombre ?? '',
                    $v->grosor,
                    $v->color,
                    $v->marca,
                    $v->cm,
                    $v->precio, // Assuming precio is USD or base logic
                    $v->precio * \Illuminate\Support\Facades\Cache::get('bcv_rate', 1),
                    $v->en_oferta ? 'Sí' : 'No',
                    $v->descuento_porcentaje,
                    $v->stock_base
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function restockReport()
    {
        $agotados = ProductoVariante::with('producto')
            ->whereNull('parent_id')
            ->where('stock_base', '<=', 5)
            ->orderBy('stock_base', 'asc')
            ->get();
            
        return view('admin.products.restock-report', compact('agotados'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('admin.products.create', compact('productos', 'proveedores'));
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
            'talla'                => 'nullable|string|max:50',
            'unidad_medida'        => 'required|string',
            'empaques'             => 'nullable|array',
            'empaques.*.nombre'    => 'required|string|max:100',
            'empaques.*.factor'    => 'required|integer|min:1',
            'empaques.*.precio'    => 'required|numeric|min:0',
            'en_oferta'            => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock_base'           => 'required|integer|min:0',
            'precio'               => 'required|numeric|min:0', // Precio USD
            'proveedor_id'         => 'nullable|exists:proveedores,id',
            'imagen'               => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->filled('producto_id')) {
                $producto = Producto::findOrFail($request->producto_id);
            } else {
                $categoriaStr = $request->categoria;
                $categoria = Categoria::firstOrCreate(['nombre' => $categoriaStr]);

                $producto = Producto::create([
                    'categoria_id' => $categoria->id,
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                ]);
            }

            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('img/productos'), $filename);
                $imagenPath = 'img/productos/' . $filename;
            }

            // Create Master Variant (Stock holder)
            $detalle = ProductoVariante::create([
                'producto_id'          => $producto->id,
                'parent_id'            => null,
                'unidad_medida'        => $request->unidad_medida,
                'factor_conversion'    => 1, // Unidad base siempre es factor 1
                'grosor'               => $request->grosor,
                'color'                => $request->color,
                'talla'                => $request->talla,
                'marca'                => $request->marca,
                'precio'               => $request->precio,
                'stock_base'           => $request->stock_base,
                'proveedor_id'         => $request->proveedor_id,
                'en_oferta'            => $request->boolean('en_oferta'),
                'descuento_porcentaje' => $request->descuento_porcentaje ?? 0,
                'imagen'               => $imagenPath,
            ]);

            // Create Child Variants (Empaques) via Self-Referencing
            if ($request->has('empaques') && count($request->empaques) > 0) {
                foreach($request->empaques as $emp) {
                    ProductoVariante::create([
                        'producto_id'          => $producto->id,
                        'parent_id'            => $detalle->id,
                        'unidad_medida'        => $emp['nombre'],
                        'factor_conversion'    => $emp['factor'],
                        'precio'               => $emp['precio'],
                        
                        // Herencia
                        'grosor'               => $request->grosor,
                        'color'                => $request->color,
                        'talla'                => $request->talla,
                        'marca'                => $request->marca,
                        'stock_base'           => 0, // Children do not hold stock
                        'proveedor_id'         => $detalle->proveedor_id,
                        'en_oferta'            => false,
                        'descuento_porcentaje' => 0,
                        'imagen'               => $imagenPath,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Producto y variante creados correctamente bajo modelo de 12 tablas.');
    }

    public function edit(int $id)
    {
        $variante  = ProductoVariante::with('producto.categoria', 'empaques')->findOrFail($id);
        $productos = Producto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();
        
        $colores = ProductoVariante::whereNotNull('color')->distinct()->pluck('color')->toJson();
        $grosores = ProductoVariante::whereNotNull('grosor')->distinct()->pluck('grosor')->toJson();
        $marcas = ProductoVariante::whereNotNull('marca')->distinct()->pluck('marca')->toJson();

        return view('admin.products.edit', compact('variante', 'productos', 'colores', 'grosores', 'marcas', 'proveedores'));
    }

    public function update(Request $request, int $id)
    {
        $variante = ProductoVariante::findOrFail($id);

        $request->validate([
            'producto_id'          => 'nullable|exists:productos,id',
            'nombre'               => 'required_if:producto_id,' . $variante->producto_id . '|required_without:producto_id|nullable|string|max:150',
            'categoria'            => 'required_if:producto_id,' . $variante->producto_id . '|required_without:producto_id|nullable|string|max:100',
            'descripcion'          => 'nullable|string',
            'grosor'               => 'nullable|string|max:50',
            'color'                => 'nullable|string|max:50',
            'marca'                => 'nullable|string|max:100',
            'talla'                => 'nullable|string|max:50',
            'unidad_medida'        => 'required|string',
            'empaques'             => 'nullable|array',
            'empaques.*.nombre'    => 'required|string|max:100',
            'empaques.*.factor'    => 'required|integer|min:1',
            'empaques.*.precio'    => 'required|numeric|min:0',
            'en_oferta'            => 'nullable|boolean',
            'descuento_porcentaje' => 'nullable|integer|min:0|max:100',
            'stock_base'           => 'required|numeric|min:0',
            'precio'               => 'required|numeric|min:0',
            'proveedor_id'         => 'nullable|exists:proveedores,id',
            'imagen'               => 'nullable|image|max:2048',
        ]);

        if ($request->filled('producto_id')) {
            if ($request->producto_id == $variante->producto_id) {
                // Actualizar info basica del producto si mantuve el mismo ID
                $cat = Categoria::firstOrCreate(['nombre' => $request->categoria]);
                $variante->producto->update([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'categoria_id' => $cat->id
                ]);
            } else {
                $variante->producto_id = $request->producto_id;
                $variante->save();
            }
        } else {
            $cat = Categoria::firstOrCreate(['nombre' => $request->categoria]);
            $producto = Producto::create([
                'categoria_id' => $cat->id,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);
            $variante->producto_id = $producto->id;
            $variante->save();
        }

        $data = $request->only('grosor', 'color', 'marca', 'talla', 'stock_base', 'precio', 'unidad_medida', 'proveedor_id');
        $data['en_oferta']            = $request->boolean('en_oferta');
        $data['descuento_porcentaje'] = $request->descuento_porcentaje ?? 0;

        if ($request->hasFile('imagen')) {
            // Eliminar antigua si existe
            if ($variante->imagen && file_exists(public_path($variante->imagen))) {
                unlink(public_path($variante->imagen));
            } elseif ($variante->imagen && Storage::disk('public')->exists($variante->imagen)) {
                Storage::disk('public')->delete($variante->imagen);
            }
            
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/productos'), $filename);
            $data['imagen'] = 'img/productos/' . $filename;
        }

        $variante->update($data);

        // Actualizar/Recrear hijos (Empaques)
        ProductoVariante::where('parent_id', $variante->id)->delete();
        if ($request->has('empaques') && count($request->empaques) > 0) {
            foreach($request->empaques as $emp) {
                ProductoVariante::create([
                    'producto_id'          => $variante->producto_id,
                    'parent_id'            => $variante->id,
                    'unidad_medida'        => $emp['nombre'],
                    'factor_conversion'    => $emp['factor'],
                    'precio'               => $emp['precio'],
                    'grosor'               => $request->grosor,
                    'color'                => $request->color,
                    'talla'                => $request->talla,
                    'marca'                => $request->marca,
                    'stock_base'           => 0, // Master handles it
                    'proveedor_id'         => $variante->proveedor_id,
                    'en_oferta'            => false,
                    'descuento_porcentaje' => 0,
                    'imagen'               => $variante->imagen,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Variante actualizada y sincronizada en ERP.');
    }

    public function destroy(int $id)
    {
        $variante = ProductoVariante::findOrFail($id);
        
        if ($variante->imagen && file_exists(public_path($variante->imagen))) {
            unlink(public_path($variante->imagen));
        } elseif ($variante->imagen && Storage::disk('public')->exists($variante->imagen)) {
            Storage::disk('public')->delete($variante->imagen);
        }
        
        // Cascades to children
        ProductoVariante::where('parent_id', $variante->id)->delete();
        $variante->delete();
        
        return back()->with('success', 'Variante eliminada completamente del ERP.');
    }
}
