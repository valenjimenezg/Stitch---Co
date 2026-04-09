<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        $orden = $request->input('orden', 'relevancia');

        $query = DetalleProducto::with('producto')
            ->whereHas('producto', function ($queryBuilder) use ($q) {
                if (!empty($q)) {
                    $queryBuilder->where('nombre', 'like', "%{$q}%")
                                 ->orWhere('categoria', 'like', "%{$q}%");
                }
            });

        // Ordenamiento
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio_con_descuento', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_con_descuento', 'desc');
                break;
            case 'ofertas':
                $query->where('en_oferta', true)->orderBy('precio_con_descuento', 'asc');
                break;
            default:
                // Relevancia: Dejarlo como venga (tal vez últimos creados)
                $query->whereHas('producto', function($qBuilder) {
                    $qBuilder->orderBy('created_at', 'desc');
                });
                break;
        }

        $variantes = $query->paginate(20)->withQueryString();

        return view('search.index', compact('variantes', 'q', 'orden'));
    }

    public function query(Request $request)
    {
        $termino = $request->input('q', '');

        if (strlen($termino) < 2) {
            return response()->json([]);
        }

        // Busca productos que contengan la palabra en su nombre
        // Nota: en tu BD la "marca" y los precios están en la tabla "detalle_productos"
        // por lo que usamos ->with('detalleProductos') en lugar de ->with('marca')
        $productos = \App\Models\Producto::where('nombre', 'LIKE', '%' . $termino . '%')
            ->has('detalleProductos')    
            ->with('detalleProductos')
            ->take(15) // Consultamos más de 6 previendo los duplicados
            ->get();

        $resultados = $productos->map(function ($producto) {
            // Tomamos el primer detalle/variante para mostrar su precio e imagen principal
            $variante = $producto->detalleProductos->first();

            // Agregar diferenciador de variante si existe para evitar confusiones de nombres duplicados
            $nombreDisplay = $producto->nombre;
            if ($variante && ($variante->color || $variante->grosor)) {
                $extras = implode(' | ', array_filter([$variante->grosor, $variante->color]));
                if ($extras) {
                    $nombreDisplay .= ' (' . $extras . ')';
                }
            }

            return [
                // El frontend necesita el ID de la variante para la URL de products.show
                'id'         => $variante ? $variante->id : $producto->id,
                'nombre'     => $nombreDisplay,
                'precio'     => $variante ? bs($variante->precio_con_descuento) : '—',
                'precio_usd' => $variante ? number_format($variante->precio_con_descuento, 2) : '0.00',
                'miniatura'  => ($variante && $variante->imagen) ? asset($variante->imagen) : null,
                'url'        => $variante ? route('products.show', $variante->id) : '#',
            ];
        })->unique('nombre')->take(8)->values();

        return response()->json($resultados);
    }
}
