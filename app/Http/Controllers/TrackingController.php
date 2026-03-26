<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'email' => 'required|email|max:255',
        ]);

        $order = Venta::with('detalles.variante.producto')
            ->where('id', $request->order_id)
            ->where('email', $request->email)
            ->first();

        if (! $order) {
            return back()->with('error', 'No encontramos ningún pedido con ese ID y correo. Por favor, verifica tus datos e intenta de nuevo.');
        }

        return view('tracking.index', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $order = Venta::where('id', $id)->firstOrFail();

        // Seguridad: el correo del botón cancelar debe coincidir
        if ($order->email !== $request->email) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        if ($order->estado === 'pendiente') {
            DB::transaction(function () use ($order) {
                $order->update(['estado' => 'cancelado']);

                foreach ($order->detalles as $detalle) {
                    $detalle->variante->increment('stock', $detalle->cantidad);
                }
            });

            return redirect()->route('tracking.index')->with('success', 'Tu pedido ha sido cancelado exitosamente y el stock fue liberado.');
        }

        return back()->with('error', 'Este pedido ya no puede ser cancelado porque se encuentra en proceso.');
    }
}
