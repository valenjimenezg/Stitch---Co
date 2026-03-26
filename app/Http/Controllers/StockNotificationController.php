<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'variante_id' => 'required|exists:detalle_productos,id',
            'email' => 'required|email|max:255',
        ]);

        // Evitar duplicados para la misma variante y correo no notificado
        \App\Models\NotificacionStock::firstOrCreate([
            'variante_id' => $request->variante_id,
            'email' => $request->email,
            'procesado' => false
        ]);

        return back()->with('success', '¡Anotado! Te avisaremos encuanto repongamos el stock de este producto.');
    }
}
