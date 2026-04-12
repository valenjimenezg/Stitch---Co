<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\InventarioLog;
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

        $order = Orden::with('detalles.variante.producto')
            ->where('id', $request->order_id)
            ->whereHas('user', function($q) use ($request) {
                $q->where('email', $request->email);
            })
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

        $order = Orden::where('id', $id)->firstOrFail();

        // Seguridad: validar que el usuario dueño está ingresando su mismo email
        if (!$order->user || $order->user->email !== $request->email) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        if ($order->estado === 'pendiente') {
            DB::transaction(function () use ($order) {
                $order->update(['estado' => 'cancelada']);

                foreach ($order->detalles as $detalle) {
                    if ($detalle->variante) {
                        $varianteBase = $detalle->variante->parent_id ? \App\Models\ProductoVariante::find($detalle->variante->parent_id) : $detalle->variante;
                        $factorConversion = $detalle->variante->factor_conversion ?: 1;
                        $cantidadBase = $detalle->cantidad * $factorConversion;
                        
                        $varianteBase->increment('stock_base', $cantidadBase);
                        
                        InventarioLog::create([
                            'variante_id' => $varianteBase->id,
                            'orden_id'    => $order->id,
                            'cantidad'    => $cantidadBase,
                            'tipo'        => 'entrada',
                            'motivo'      => 'Devolución: Cancelada vía Tracking por Cliente',
                        ]);
                    }
                }
            });

            return redirect()->route('tracking.index')->with('success', 'Tu pedido ha sido cancelado exitosamente y el stock fue liberado.');
        }

        return back()->with('error', 'Este pedido ya no puede ser cancelado porque se encuentra procesado o despachado.');
    }
}
