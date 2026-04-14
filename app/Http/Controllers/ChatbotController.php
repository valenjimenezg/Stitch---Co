<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Busca productos por nombre/keyword para el asistente virtual Costurín.
     * Devuelve info de stock, variantes, colores, unidades y precio.
     */
    public function buscarProducto(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['found' => false, 'message' => 'Consulta muy corta.']);
        }

        // Buscar productos por nombre (fuzzy)
        $productos = Producto::with(['variantes' => function ($q) {
                $q->whereNull('parent_id') // Solo variantes principales, no empaques
                  ->whereNull('deleted_at')
                  ->select('id', 'producto_id', 'color', 'grosor', 'marca', 'unidad_medida',
                           'stock_base', 'precio', 'precio_usd', 'en_oferta',
                           'descuento_porcentaje', 'parent_id');
            }, 'categoria'])
            ->where('nombre', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();

        if ($productos->isEmpty()) {
            // Segundo intento: buscar en variantes por color/grosor/marca
            $variantesMatch = ProductoVariante::with(['producto.categoria'])
                ->whereNull('parent_id')
                ->whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    $q->where('color', 'LIKE', "%{$query}%")
                      ->orWhere('grosor', 'LIKE', "%{$query}%")
                      ->orWhere('marca', 'LIKE', "%{$query}%");
                })
                ->limit(3)
                ->get();

            if ($variantesMatch->isEmpty()) {
                return response()->json([
                    'found' => false,
                    'message' => "No encontré productos con ese nombre en nuestro catálogo. ¿Quieres intentar con otra palabra?"
                ]);
            }

            // Agrupamos por producto
            $productos = $variantesMatch->pluck('producto')->unique('id');
        }

        $resultados = [];

        foreach ($productos as $producto) {
            $variantes = $producto->variantes ?? collect();

            // Colores disponibles
            $colores = $variantes->whereNotNull('color')
                ->pluck('color')->filter()->unique()->values()->toArray();

            // Grosores disponibles
            $grosores = $variantes->whereNotNull('grosor')
                ->pluck('grosor')->filter()->unique()->values()->toArray();

            // Unidades de medida
            $unidades = $variantes->whereNotNull('unidad_medida')
                ->pluck('unidad_medida')->filter()->unique()->values()->toArray();

            // Stock total
            $stockTotal = $variantes->sum('stock_base');
            $hayStock   = $stockTotal > 0;

            // Variantes con stock > 0
            $variantesConStock = $variantes->where('stock_base', '>', 0);
            $agotadas          = $variantes->where('stock_base', '<=', 0)->count();

            // Precio rango
            $precios   = $variantes->pluck('precio_usd')->filter()->sort()->values();
            $precioMin = $precios->first();
            $precioMax = $precios->last();

            // ¿Alguna variante en oferta?
            $enOferta = $variantes->where('en_oferta', true)->count() > 0;

            $resultados[] = [
                'id'           => $producto->id,
                'nombre'       => $producto->nombre,
                'categoria'    => $producto->categoria?->nombre ?? 'Sin categoría',
                'descripcion'  => $producto->descripcion,
                'hay_stock'    => $hayStock,
                'stock_total'  => $stockTotal,
                'colores'      => $colores,
                'grosores'     => $grosores,
                'unidades'     => $unidades,
                'precio_min'   => $precioMin,
                'precio_max'   => $precioMax,
                'en_oferta'    => $enOferta,
                'total_variantes'    => $variantes->count(),
                'variantes_activas'  => $variantesConStock->count(),
                'variantes_agotadas' => $agotadas,
            ];
        }

        return response()->json([
            'found'      => true,
            'resultados' => $resultados,
        ]);
    }
}
