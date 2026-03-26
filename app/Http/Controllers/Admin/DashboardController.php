<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\User;
use App\Models\Venta;
use App\Models\DetalleProducto;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVentas  = Venta::sum('total_venta');
        $pedidosMes   = Venta::whereMonth('created_at', now()->month)->count();
        $stockTotal   = DetalleProducto::sum('stock');
        $stockBajo    = DetalleProducto::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $topProductos = DetalleVenta::with('variante.producto')
            ->selectRaw('variante_id, COUNT(*) as total_pedidos')
            ->groupBy('variante_id')
            ->orderByDesc('total_pedidos')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalVentas', 'pedidosMes', 'stockTotal', 'stockBajo', 'topProductos'));
    }

    public function clients()
    {
        $clientes = User::where('rol', 'cliente')->latest()->paginate(20);
        return view('admin.clients', compact('clientes'));
    }

    public function exportClients()
    {
        $clientes = User::where('rol', 'cliente')->latest()->get();
        $filename = "clientes_stitch_" . date('Y-m-d') . ".csv";
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$filename", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate", "Expires" => "0"];
        
        $callback = function() use($clientes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Apellido', 'CI', 'Email', 'Telefono', 'Registrado']);
            foreach ($clientes as $c) {
                fputcsv($file, [$c->id, $c->nombre, $c->apellido, $c->cedula_identidad, $c->email, $c->telefono, $c->created_at->format('Y-m-d')]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function newsletters()
    {
        $suscritos = \App\Models\NewsletterSubscriber::latest()->paginate(20);
        return view('admin.newsletters', compact('suscritos'));
    }

    public function exportNewsletters()
    {
        $suscritos = \App\Models\NewsletterSubscriber::latest()->get();
        $filename = "suscriptores_stitch_co_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Email', 'Fecha de Registro'];

        $callback = function() use($suscritos, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($suscritos as $sub) {
                fputcsv($file, [
                    $sub->id,
                    $sub->email,
                    $sub->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateBcv()
    {
        \Illuminate\Support\Facades\Artisan::call('bcv:update-prices');
        return back()->with('success', 'Precios en Bolívares actualizados con la tasa del BCV exitosamente.');
    }

    public function stockNotifications()
    {
        $notificaciones = \App\Models\NotificacionStock::with('variante.producto')->latest()->paginate(20);
        return view('admin.stock-notifications', compact('notificaciones'));
    }

    public function updateStockNotification(\Illuminate\Http\Request $request, int $id)
    {
        $notificacion = \App\Models\NotificacionStock::findOrFail($id);
        $notificacion->update(['procesado' => true]);
        return back()->with('success', 'La solicitud de notificación ha sido marcada como resuelta.');
    }

    public function ventasMensuales()
    {
        $meses = collect(range(1, 6))->map(function ($m) {
            return [
                'mes'   => now()->subMonths(6 - $m)->format('M'),
                'total' => Venta::whereMonth('created_at', now()->subMonths(6 - $m)->month)->sum('total_venta'),
            ];
        });

        return response()->json([
            'labels'   => $meses->pluck('mes'),
            'datasets' => [[
                'label'           => 'Ventas (Bs.)',
                'data'            => $meses->pluck('total'),
                'borderColor'     => '#8b52ff',
                'backgroundColor' => 'rgba(139,82,255,0.1)',
                'tension'         => 0.4,
                'fill'            => true,
            ]],
        ]);
    }

    public function ventasCategoria()
    {
        $categorias = DetalleVenta::with('variante.producto')
            ->get()
            ->groupBy(fn($d) => $d->variante->producto->categoria ?? 'Otros')
            ->map(fn($group) => $group->count());

        return response()->json([
            'labels'   => $categorias->keys(),
            'datasets' => [[
                'data'            => $categorias->values(),
                'backgroundColor' => ['#8b52ff', '#6d3de0', '#a78bfa', '#c4b5fd', '#ede9fe'],
            ]],
        ]);
    }
}
