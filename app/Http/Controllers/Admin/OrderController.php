<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('ventas'));
    }

    public function payments()
    {
        $pagos = Venta::with(['user', 'pago'])
            ->where('estado', 'pendiente')
            ->whereIn('metodo_pago', ['pago_movil', 'transferencia', 'transferencia_p2p'])
            ->latest()
            ->paginate(20);
            
        return view('admin.payments.index', compact('pagos'));
    }

    public function shipping()
    {
        $envios = Venta::with(['user', 'detalles.variante.producto'])
            ->whereIn('estado', ['procesando', 'enviado'])
            ->latest()
            ->paginate(20);
            
        return view('admin.shipping.index', compact('envios'));
    }

    public function show($id)
    {
        $venta = Venta::with(['user', 'detalles.variante.producto', 'factura'])->findOrFail($id);
        return view('admin.orders.show', compact('venta'));
    }

    public function export()
    {
        $ventas = Venta::with('user')->latest()->get();
        $filename = "pedidos_stitch_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache", "Cache-Control" => "must-revalidate, post-check=0, pre-check=0", "Expires" => "0"
        ];

        $columns = ['ID', 'Cliente', 'Email', 'Fecha', 'Total Bs', 'Metodo Pago', 'Banco', 'Ref', 'Estado'];

        $callback = function() use($ventas, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($ventas as $v) {
                fputcsv($file, [
                    $v->id, $v->user->nombre ?? 'Invitado', $v->user->email ?? 'N/A',
                    $v->created_at->format('Y-m-d H:i'), number_format((float) $v->total_venta, 2, '.', ''),
                    $v->metodo_pago, $v->banco_pago, $v->referencia_pago, $v->estado
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado']);

        $venta = Venta::with('detalles.variante')->findOrFail($id);
        $estadoAnterior = $venta->estado;
        
        $venta->update(['estado' => $request->estado]);

        // Generación Inmutable de Factura
        if (in_array($request->estado, ['procesando', 'enviado', 'entregado']) && !$venta->factura) {
            \App\Models\Factura::create([
                'venta_id' => $venta->id,
                'monto' => $venta->total_venta,
                'fecha_emision' => now(),
            ]);
        }

        // Devolución estricta de Stock al Kardex si la Orden es CANCELADA desde el panel
        if ($request->estado === 'cancelado' && $estadoAnterior !== 'cancelado') {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $detalle->variante->increment('stock', $detalle->cantidad);
                    \App\Models\MovimientoInventario::create([
                        'variante_id' => $detalle->variante->id,
                        'venta_id' => $venta->id,
                        'cantidad' => $detalle->cantidad,
                        'tipo' => 'entrada',
                        'motivo' => 'Devolución: Orden Cancelada por Admin',
                    ]);
                }
            }
        }

        return back()->with('success', 'El estado del pedido ha sido actualizado sincronizadamente.');
    }

    public function approvePayment($id)
    {
        $venta = Venta::with(['user', 'detalles.variante.producto', 'factura'])->findOrFail($id);
        
        if ($venta->estado !== 'pendiente') {
            return back()->with('error', 'El pago solo puede verificarse si el pedido está Pendiente.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($venta) {
                $venta->update(['estado' => 'procesando']);

                // 1. Generamos registro de factura oficial si no lo tenía ya
                if (!$venta->factura) {
                    \App\Models\Factura::create([
                        'venta_id' => $venta->id,
                        'monto' => $venta->total_venta,
                        'fecha_emision' => now(),
                    ]);
                    $venta->refresh(); // Para obtener el objeto factura si lo requerimos luego
                }

                // 2. Compilar PDF en Memoria
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', ['venta' => $venta]);
                $pdfContent = $pdf->output();

                // 3. Enviar Correo con el Adjunto
                if ($venta->user && $venta->user->email) {
                    \Illuminate\Support\Facades\Mail::to($venta->user->email)->send(
                        new \App\Mail\FacturaAprobadaMail($venta, $pdfContent)
                    );
                }
            });

            return back()->with('success', '¡Fondos verificados correctamente! El pedido ha avanzado a Procesando y se envió la factura al correo del cliente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error procesando el pago y el envío de correo: ' . $e->getMessage());
        }
    }

    public function marcarEntregado($id)
    {
        $venta = Venta::findOrFail($id);
        $venta->update(['estado' => 'entregado']);

        // Generación de factura si se requiere el registro
        if (!$venta->factura) {
            \App\Models\Factura::create([
                'venta_id' => $venta->id,
                'monto' => $venta->total_venta,
                'fecha_emision' => now(),
            ]);
        }

        return back()->with('success', 'Pedido completado y factura generada');
    }

    public function factura(int $id)
    {
        $venta = Venta::with(['user', 'detalles.variante.producto', 'factura'])->findOrFail($id);
        
        if (!$venta->factura) {
            abort(404, 'Factura no disponible.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', compact('venta'));
        return $pdf->stream('factura_STITCH_ORD-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function destroy($id)
    {
        $venta = Venta::with('detalles.variante')->findOrFail($id);

        // Validación de seguridad (no eliminar si está en progreso, pagado o despachado)
        if (in_array($venta->estado, ['pagado', 'procesando', 'enviado', 'entregado'])) {
            abort(403, 'Acceso Denegado: No puedes eliminar un pedido que ya ha sido pagado, procesado o entregado.');
        }

        // Si estaba pendiente, se asume que retuvo stock que nunca fue devuelto porque no se canceló.
        // Si ya estaba 'cancelado', el stock ya debería haberse devuelto en el updateStatus, 
        // pero validamos si por alguna razón no se devolvió o queremos forzarlo según la petición.
        // Asumimos que si no es cancelado se debe devolver.
        if ($venta->estado === 'pendiente' || $venta->estado === 'pending') {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $detalle->variante->increment('stock', $detalle->cantidad);
                    \App\Models\MovimientoInventario::create([
                        'variante_id' => $detalle->variante->id,
                        'venta_id'    => $venta->id,
                        'cantidad'    => $detalle->cantidad,
                        'tipo'        => 'entrada',
                        'motivo'      => 'Devolución de retención por Inactividad (Eliminado >48h)',
                    ]);
                }
            }
        }

        $venta->delete(); // Usará SoftDeletes

        return back()->with('success', 'Pedido eliminado del panel exitosamente.');
    }

    public function destroyCancelled()
    {
        // Limpiamos todos los cancelados por bloque. El stock ya fue retornado por el evento updateStatus.
        $cancelados = Venta::query()->where('estado', 'cancelado')->get();
        if ($cancelados->isEmpty()) {
            return back()->with('info', 'No hay pedidos cancelados para limpiar.');
        }

        foreach ($cancelados as $venta) {
            /** @var \App\Models\Venta $venta */
            $venta->delete();
        }

        return back()->with('success', 'Se han limpiado todos los pedidos cancelados del panel.');
    }

    public function generateInvoice(Venta $order)
    {
        if (!$order->invoice_number) {
            $order->update(['invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)]);
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.pdf', compact('order'));
        return $pdf->stream('factura-'.$order->invoice_number.'.pdf');
    }

    public function markAsPickedUp(Venta $order)
    {
        $order->update([
            'estado' => 'entregado', 
            'completed_at' => now()
        ]);
        return back()->with('success', 'Pedido entregado en mostrador exitosamente.');
    }

    public function markAsDeliveredLocally(Venta $order)
    {
        $order->update([
            'estado' => 'entregado', 
            'completed_at' => now()
        ]);
        return back()->with('success', 'Se confirmó la entrega por el motorizado.');
    }
}
