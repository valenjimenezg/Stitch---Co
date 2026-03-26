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
        $pagos = Venta::with('user')
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

    public function factura(int $id)
    {
        $venta = Venta::with(['user', 'detalles.variante.producto', 'factura'])->findOrFail($id);
        
        if (!$venta->factura) {
            abort(404, 'Factura no disponible.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', compact('venta'));
        return $pdf->stream('factura_STITCH_ORD-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
