<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function query(Request $request)
    {
        $q = $request->input('q', '');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $resultados = DetalleProducto::with('producto')
            ->whereHas('producto', fn($query) => $query->where('nombre', 'like', "%{$q}%"))
            ->take(8)
            ->get()
            ->map(fn($v) => [
                'id'     => $v->id,
                'nombre' => $v->producto->nombre ?? '—',
                'precio' => 'Bs. ' . number_format($v->precio_con_descuento, 2),
                'url'    => route('products.show', $v->id),
            ]);

        return response()->json($resultados);
    }
}
