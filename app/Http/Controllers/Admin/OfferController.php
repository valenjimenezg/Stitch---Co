<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductoVariante::with('producto.categoria');
        
        if ($request->has('buscar') && $request->buscar != '') {
            $query->whereHas('producto', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhereHas('categoria', function($cb) use ($request) {
                      $cb->where('nombre', 'like', '%' . $request->buscar . '%');
                  });
            });
        }
        
        $variantes = $query->orderByDesc('en_oferta')->paginate(50);
        return view('admin.offers.index', compact('variantes'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'variantes' => 'required|array',
            'descuento' => 'nullable|integer|min:0|max:100',
            'accion'    => 'required|in:activar,desactivar'
        ]);

        $enOferta = $request->accion === 'activar';
        $descuento = $enOferta ? ($request->descuento ?? 0) : 0;

        ProductoVariante::whereIn('id', $request->variantes)->update([
            'en_oferta' => $enOferta,
            'descuento_porcentaje' => $descuento
        ]);

        $mensaje = $enOferta 
            ? "Oferta del {$descuento}% aplicada a " . count($request->variantes) . " producto(s)."
            : "Ofertas retiradas de " . count($request->variantes) . " producto(s).";

        return back()->with('success', $mensaje);
    }
}
