<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orden;
use App\Models\InventarioLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $ventas = Orden::with('user')
            ->whereNotIn('estado', ['carrito'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('ventas'));
    }

    public function payments()
    {
        $pagos = Orden::with('user')
            ->where('estado', 'pendiente')
            ->whereIn('metodo_pago', ['pago_movil', 'transferencia', 'transferencia_p2p'])
            ->latest()
            ->paginate(20);
            
        return view('admin.payments.index', compact('pagos'));
    }

    public function shipping()
    {
        $envios = Orden::with(['user', 'detalles.variante.producto'])
            ->whereIn('estado', ['procesando', 'enviado'])
            ->latest()
            ->paginate(20);
            
        return view('admin.shipping.index', compact('envios'));
    }

    public function show($id)
    {
        $venta = Orden::with(['user', 'detalles.variante.producto'])->findOrFail($id);
        
        // El número de factura y datos de pago ahora viven dentro de la misma Orden.
        // No necesitamos buscar en otra tabla. Simplemente usamos $venta.

        return view('admin.orders.show', compact('venta'));
    }

    public function export()
    {
        $ventas = Orden::with('user')->whereNotIn('estado', ['carrito'])->latest()->get();
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
                    $v->created_at->format('Y-m-d H:i'), number_format((float) $v->total_amount, 2, '.', ''),
                    $v->metodo_pago, $v->banco_pago, $v->referencia_pago, $v->estado
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelada']);

        $venta = Orden::with('detalles.variante')->findOrFail($id);
        $estadoAnterior = $venta->estado;
        
        $venta->update(['estado' => $request->estado]);

        // Generación Inmutable de Factura
        if (in_array($request->estado, ['procesando', 'enviado', 'entregado']) && !$venta->invoice_number) {
            $venta->update([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($venta->id, 5, '0', STR_PAD_LEFT)
            ]);
        }

        // Devolución estricta de Stock al Kardex si la Orden es CANCELADA desde el panel
        if ($request->estado === 'cancelada' && $estadoAnterior !== 'cancelada') {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $varianteBase = $detalle->variante->parent_id ? \App\Models\ProductoVariante::find($detalle->variante->parent_id) : $detalle->variante;
                    
                    // Asumimos que la cantidad guardada en detalle es la unidad final del hijo o la comprada base.
                    // Esto se calculó durante checkout, pero para reversar simplemente multiplicamos
                    $factorConversion = $detalle->variante->factor_conversion ?: 1;
                    $cantidadBase = $detalle->cantidad * $factorConversion;

                    $varianteBase->increment('stock_base', $cantidadBase);
                    
                    InventarioLog::create([
                        'variante_id' => $varianteBase->id,
                        'orden_id' => $venta->id,
                        'cantidad_cambio' => $cantidadBase,
                        'motivo' => 'Devolución: Orden Cancelada por Admin',
                    ]);
                }
            }
        }

        return back()->with('success', 'El estado del pedido ha sido actualizado sincronizadamente.');
    }

    public function approvePayment($id)
    {
        $venta = Orden::with(['user', 'detalles.variante.producto'])->findOrFail($id);
        
        if ($venta->estado !== 'pendiente') {
            return back()->with('error', 'El pago solo puede verificarse si el pedido está Pendiente.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($venta) {
                // 1. Assign invoice number and update state
                $invoiceNumber = $venta->invoice_number ?: 'INV-' . date('Ymd') . '-' . str_pad($venta->id, 5, '0', STR_PAD_LEFT);

                $venta->update([
                    'estado' => 'procesando',
                    'invoice_number' => $invoiceNumber
                ]);

                // 2. Compilar PDF en Memoria usando la vista compatible (que ahora debe leer $venta->invoice_number)
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
        $venta = Orden::findOrFail($id);
        $invoiceNumber = $venta->invoice_number ?: 'INV-' . date('Ymd') . '-' . str_pad($venta->id, 5, '0', STR_PAD_LEFT);

        $venta->update([
            'estado' => 'entregado', 
            'completed_at' => now(),
            'invoice_number' => $invoiceNumber
        ]);

        return back()->with('success', 'Pedido completado y factura generada');
    }

    public function factura(int $id)
    {
        $venta = Orden::with(['user', 'detalles.variante.producto'])->findOrFail($id);

        if (!$venta->invoice_number) {
            abort(404, 'Factura no disponible.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', compact('venta'));
        return $pdf->stream('factura_STITCH_' . $venta->invoice_number . '.pdf');
    }

    public function destroy($id)
    {
        $venta = Orden::with('detalles.variante')->findOrFail($id);

        if (in_array($venta->estado, ['procesando', 'enviado', 'entregado'])) {
            abort(403, 'Acceso Denegado: No puedes eliminar un pedido que ya ha sido pagado, procesado o entregado.');
        }

        if ($venta->estado === 'pendiente' || $venta->estado === 'pending') {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $varianteBase = $detalle->variante->parent_id ? \App\Models\ProductoVariante::find($detalle->variante->parent_id) : $detalle->variante;
                    $factorConversion = $detalle->variante->factor_conversion ?: 1;
                    $cantidadBase = $detalle->cantidad * $factorConversion;
                    
                    $varianteBase->increment('stock_base', $cantidadBase);
                    
                    InventarioLog::create([
                        'variante_id' => $varianteBase->id,
                        'orden_id'    => $venta->id,
                        'cantidad_cambio' => $cantidadBase,
                        'motivo'      => 'Devolución de retención por Inactividad (Eliminado >48h)',
                    ]);
                }
            }
        }

        $venta->delete();

        return back()->with('success', 'Pedido eliminado del panel exitosamente.');
    }

    public function destroyCancelled()
    {
        $cancelados = Orden::query()->where('estado', 'cancelada')->get();
        if ($cancelados->isEmpty()) {
            return back()->with('info', 'No hay pedidos cancelados para limpiar.');
        }

        /** @var \App\Models\Orden $venta */
        foreach ($cancelados as $venta) {
            $venta->delete();
        }

        return back()->with('success', 'Se han limpiado todos los pedidos cancelados del panel.');
    }

    public function generateInvoice(Orden $order)
    {
        if (!$order->invoice_number) {
            $order->update(['invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)]);
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.pdf', compact('order'));
        return $pdf->stream('factura-'.$order->invoice_number.'.pdf');
    }

    public function markAsPickedUp(Orden $order)
    {
        $order->update([
            'estado' => 'entregado', 
            'completed_at' => now()
        ]);
        return back()->with('success', 'Pedido entregado en mostrador exitosamente.');
    }

    public function markAsDeliveredLocally(Orden $order)
    {
        $order->update([
            'estado' => 'entregado', 
            'completed_at' => now()
        ]);
        return back()->with('success', 'Se confirmó la entrega por el motorizado.');
    }
}
