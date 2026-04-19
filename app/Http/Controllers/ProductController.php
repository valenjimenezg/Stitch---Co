<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;

class ProductController extends Controller
{
    public function show(int $id)
    {
        $variante = ProductoVariante::with(['producto.categoria', 'producto.variantes', 'producto.comentarios.user'])->findOrFail($id);

        $relacionados = ProductoVariante::with(['producto.variantes', 'producto.categoria'])
            ->whereHas('producto', fn($q) => $q->where('categoria_id', $variante->producto->categoria_id ?? null))
            ->where('id', '!=', $id)
            ->whereNull('parent_id') // Solo mostrar bases como relacionados
            ->take(4)
            ->get();

        return view('products.show', compact('variante', 'relacionados'));
    }

    public function apiShow(int $id)
    {
        $variante = ProductoVariante::with(['producto.categoria', 'empaques'])->findOrFail($id);

        // Resolver ruta de imagen — busca en public/productos/ si la ruta no incluye ese prefijo
        $imagenRaw  = $variante->imagen;
        $imagenUrl  = null;
        if ($imagenRaw) {
            if (str_starts_with($imagenRaw, 'http')) {
                $imagenUrl = $imagenRaw;
            } elseif (file_exists(public_path($imagenRaw))) {
                $imagenUrl = asset($imagenRaw);
            } else {
                $baseName = basename($imagenRaw);
                $candidate = 'productos/' . $baseName;
                $imagenUrl = asset(file_exists(public_path($candidate)) ? $candidate : $imagenRaw);
            }
        }

        return response()->json([
            'id'                   => $variante->id,
            'nombre'               => $variante->producto->nombre ?? 'Producto',
            'marca'                => $variante->marca,
            'categoria'            => $variante->producto->categoria->nombre ?? 'Sin Categoría',
            'precio'               => $variante->precio,
            'precio_con_descuento' => $variante->en_oferta ? $variante->precio * (1 - $variante->descuento_porcentaje / 100) : $variante->precio,
            'precio_usd'           => $variante->precio_usd,
            'en_oferta'            => $variante->en_oferta,
            'descuento_porcentaje' => $variante->descuento_porcentaje,
            'color'                => $variante->color,
            'grosor'               => $variante->grosor,
            'unidad_medida'        => $variante->unidad_medida,
            'descripcion'          => $variante->producto->descripcion ?? '',
            'imagen'               => $imagenUrl,
            'stock'                => $variante->stock_disponible,
            'factor_conversion'    => $variante->factor_conversion,
            'empaques'             => $variante->empaques
        ]);
    }
}
