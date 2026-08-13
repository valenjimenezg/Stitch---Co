<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 'ventas');
        
        // Rango de fechas por defecto: primer día del mes actual hasta hoy
        $fecha_inicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fecha_fin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));

        $data = $this->getReportData($tipo, $fecha_inicio, $fecha_fin);

        return view('admin.reportes.index', array_merge([
            'tipo' => $tipo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ], $data));
    }

    public function downloadPdf(Request $request)
    {
        $tipo = $request->get('tipo', 'ventas');
        $fecha_inicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fecha_fin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));

        $data = $this->getReportData($tipo, $fecha_inicio, $fecha_fin);

        $pdf = Pdf::loadView('admin.reportes.pdf', array_merge([
            'tipo' => $tipo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ], $data));

        $filename = "Reporte_" . ucfirst($tipo) . "_" . Carbon::now()->format('Y-m-d') . ".pdf";
        return $pdf->stream($filename);
    }

    private function getReportData($tipo, $fecha_inicio, $fecha_fin)
    {
        $tasaBCV = bcv_rate();

        if ($tipo === 'ventas') {
            $ordenes = Orden::with('user')
                ->whereNotIn('estado', ['carrito'])
                ->whereDate('created_at', '>=', $fecha_inicio)
                ->whereDate('created_at', '<=', $fecha_fin)
                ->orderBy('created_at', 'desc')
                ->get();

            $ordenesValidas = $ordenes->where('estado', '!=', 'cancelada');

            $totalVentas = $ordenes->count();
            $totalIngresosUsd = $ordenesValidas->sum('total_amount');
            $totalIngresosBs = $ordenesValidas->sum(function($o) {
                return $o->total_amount * ($o->tasa_bcv_aplicada ?? bcv_rate());
            });

            $totalPedidosValidos = $ordenesValidas->count();
            $promedioTicketUsd = $totalPedidosValidos > 0 ? $totalIngresosUsd / $totalPedidosValidos : 0;

            // Agrupación por estatus
            $estatusBreakdown = $ordenes->groupBy('estado')->map->count();
            
            // Agrupación por método de pago
            $metodoPagoBreakdown = $ordenesValidas->groupBy('metodo_pago')->map->count();

            return [
                'ordenes' => $ordenes,
                'total_ventas' => $totalVentas,
                'total_ingresos_usd' => $totalIngresosUsd,
                'total_ingresos_bs' => $totalIngresosBs,
                'promedio_ticket_usd' => $promedioTicketUsd,
                'estatus_breakdown' => $estatusBreakdown,
                'metodo_pago_breakdown' => $metodoPagoBreakdown,
                'tasa_bcv' => $tasaBCV
            ];
        }

        if ($tipo === 'inventario') {
            $variantes = ProductoVariante::with(['producto.categoria', 'proveedor'])
                ->whereNull('parent_id')
                ->orderBy('stock_base', 'asc')
                ->get();

            $totalProductos = Producto::count();
            $totalVariantes = $variantes->count();
            
            $valoracionTotalUsd = $variantes->sum(function($v) {
                return $v->stock_base * $v->precio;
            });
            $valoracionTotalBs = $valoracionTotalUsd * $tasaBCV;

            $variantesCriticas = $variantes->where('stock_base', '<=', 5)->count();

            return [
                'variantes' => $variantes,
                'total_productos' => $totalProductos,
                'total_variantes' => $totalVariantes,
                'valoracion_total_usd' => $valoracionTotalUsd,
                'valoracion_total_bs' => $valoracionTotalBs,
                'variantes_criticas' => $variantesCriticas,
                'tasa_bcv' => $tasaBCV
            ];
        }

        if ($tipo === 'clientes') {
            $clientes = User::where('rol', 'cliente')
                ->with(['ordenes' => function($q) use($fecha_inicio, $fecha_fin) {
                    $q->whereNotIn('estado', ['carrito', 'cancelada'])
                      ->whereDate('created_at', '>=', $fecha_inicio)
                      ->whereDate('created_at', '<=', $fecha_fin);
                }])
                ->get()
                ->map(function($user) {
                    $user->total_pedidos = $user->ordenes->count();
                    $user->total_gastado_usd = $user->ordenes->sum('total_amount');
                    $user->total_gastado_bs = $user->ordenes->sum(function($o) {
                        return $o->total_amount * ($o->tasa_bcv_aplicada ?? bcv_rate());
                    });
                    return $user;
                })
                ->filter(function($user) {
                    return $user->total_pedidos > 0;
                })
                ->sortByDesc('total_gastado_usd')
                ->values();

            $totalClientesActivos = $clientes->count();
            $totalGastadoGeneral = $clientes->sum('total_gastado_usd');
            $promedioGastoClienteUsd = $totalClientesActivos > 0 ? $totalGastadoGeneral / $totalClientesActivos : 0;
            $clienteEstrella = $clientes->first();

            return [
                'clientes' => $clientes,
                'total_clientes_activos' => $totalClientesActivos,
                'promedio_gasto_cliente_usd' => $promedioGastoClienteUsd,
                'cliente_estrella' => $clienteEstrella,
                'tasa_bcv' => $tasaBCV
            ];
        }

        return [];
    }
}
