<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\ComentarioProducto;
use App\Models\OrdenDetalle;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'titulo'       => 'nullable|string|max:120',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'required|string|max:1000',
        ]);

        // Verificar si el usuario compró este producto (compra verificada)
        $verifiedPurchase = false;
        if (auth()->check()) {
            $verifiedPurchase = OrdenDetalle::whereHas('orden', function ($q) {
                $q->where('user_id', auth()->id())
                  ->whereIn('estado', ['completado', 'enviado', 'pagado']);
            })->whereHas('variante', function ($q) use ($producto) {
                $q->where('producto_id', $producto->id);
            })->exists();
        }

        ComentarioProducto::create([
            'user_id'          => auth()->id(),
            'producto_id'      => $producto->id,
            'titulo'           => $request->titulo,
            'calificacion'     => $request->calificacion,
            'comentario'       => $request->comentario,
            'aprobado'         => false,        // Requiere aprobación del admin
            'verified_purchase'=> $verifiedPurchase,
        ]);

        return back()->with('success', '¡Gracias por tu reseña! Será publicada tras revisión.');
    }
}
