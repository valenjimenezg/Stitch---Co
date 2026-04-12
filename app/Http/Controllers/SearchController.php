<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        $orden = $request->input('orden', 'relevancia');

        $query = ProductoVariante::with('producto')
            ->whereHas('producto', function ($queryBuilder) use ($q) {
                if (!empty($q)) {
                    $queryBuilder->where('nombre', 'like', "%{$q}%")
                                 ->orWhereHas('categoria', function($cb) use ($q){
                                     $cb->where('nombre', 'like', "%{$q}%");
                                 });
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
        $productos = \App\Models\Producto::where('nombre', 'LIKE', '%' . $termino . '%')
            ->has('variantes')    
            ->with('variantes')
            ->take(15) // Consultamos más previendo los duplicados
            ->get();

        $resultados = $productos->map(function ($producto) {
            // Tomamos la primera variante para el live search
            $variante = $producto->variantes->first();

            // Agregar diferenciador de variante si existe para evitar confusiones de nombres duplicados
            $nombreDisplay = $producto->nombre;
            if ($variante && ($variante->color || $variante->grosor || $variante->talla)) {
                $extras = implode(' | ', array_filter([$variante->grosor, $variante->color, $variante->talla]));
                if ($extras) {
                    $nombreDisplay .= ' (' . $extras . ')';
                }
            }

            return [
                'id'         => $variante ? $variante->id : $producto->id,
                'nombre'     => $nombreDisplay,
                'precio'     => $variante ? bs($variante->en_oferta ? $variante->precio * (1 - $variante->descuento_porcentaje/100) : $variante->precio) : '—',
                'precio_usd' => $variante ? number_format($variante->en_oferta ? $variante->precio * (1 - $variante->descuento_porcentaje/100) : $variante->precio, 2) : '0.00',
                'miniatura'  => ($variante && $variante->imagen) ? asset($variante->imagen) : (($producto->imagen) ? asset($producto->imagen) : null),
                'url'        => $variante ? route('products.show', $variante->id) : '#',
            ];
        })->unique('nombre')->take(8)->values();

        return response()->json($resultados);
    }
}
