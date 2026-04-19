<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComentarioProducto;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /** Lista todas las reseñas con filtros */
    public function index(Request $request)
    {
        $query = ComentarioProducto::with(['user', 'producto'])
            ->latest();

        // Filtro por estado
        if ($request->estado === 'pendiente') {
            $query->where('aprobado', false);
        } elseif ($request->estado === 'aprobado') {
            $query->where('aprobado', true);
        }

        // Filtro por calificación
        if ($request->filled('calificacion')) {
            $query->where('calificacion', $request->calificacion);
        }

        $reviews    = $query->paginate(20)->withQueryString();
        $pendientes = ComentarioProducto::where('aprobado', false)->count();

        return view('admin.reviews.index', compact('reviews', 'pendientes'));
    }

    /** Aprobar una reseña */
    public function approve($id)
    {
        ComentarioProducto::findOrFail($id)->update(['aprobado' => true]);
        return back()->with('success', 'Reseña aprobada y publicada.');
    }

    /** Rechazar (eliminar) una reseña */
    public function reject($id)
    {
        ComentarioProducto::findOrFail($id)->delete();
        return back()->with('success', 'Reseña eliminada correctamente.');
    }

    /** Guardar respuesta del admin a una reseña */
    public function respond(Request $request, $id)
    {
        $request->validate([
            'respuesta_admin' => 'required|string|max:1000',
        ]);

        ComentarioProducto::findOrFail($id)->update([
            'respuesta_admin' => $request->respuesta_admin,
            'respondido_at'   => now(),
            'aprobado'        => true, // Al responder, se aprueba automáticamente
        ]);

        return back()->with('success', 'Respuesta publicada y reseña aprobada.');
    }
}
