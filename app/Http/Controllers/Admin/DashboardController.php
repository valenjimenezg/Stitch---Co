<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\ProductoVariante;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVentas  = Orden::whereNotIn('estado', ['carrito', 'cancelada'])->sum('total_amount');
        $pedidosMes   = Orden::whereNotIn('estado', ['carrito', 'cancelada'])->whereMonth('created_at', now()->month)->count();
        $stockTotal   = ProductoVariante::whereNull('parent_id')->sum('stock_base');
        $stockBajo    = ProductoVariante::whereNull('parent_id')->where('stock_base', '<=', 5)->count();
        
        $topProductos = OrdenDetalle::with('variante.producto')
            ->whereHas('orden', fn($q) => $q->whereNotIn('estado', ['carrito', 'cancelada']))
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
                $doc = $c->tipo_documento . $c->documento_identidad;
                fputcsv($file, [$c->id, $c->nombre, $c->apellido, $doc, $c->email, $c->telefono, $c->created_at->format('Y-m-d')]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function newsletters()
    {
        $suscritos = \App\Models\NotificacionCrm::where('tipo', 'newsletter')->latest()->paginate(20);
        return view('admin.newsletters', compact('suscritos'));
    }

    public function exportNewsletters()
    {
        $suscritos = \App\Models\NotificacionCrm::where('tipo', 'newsletter')->latest()->get();
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
                    $sub->created_at ? $sub->created_at->format('Y-m-d H:i:s') : 'N/A'
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
        $notificaciones = \App\Models\NotificacionCrm::whereIn('tipo', ['stock', 'stock_alert'])->with('variante.producto')->latest()->paginate(20);
        return view('admin.stock-notifications', compact('notificaciones'));
    }

    public function updateStockNotification(\Illuminate\Http\Request $request, int $id)
    {
        $notificacion = \App\Models\NotificacionCrm::findOrFail($id);
        
        if (!$notificacion->procesado && $notificacion->variante) {
            try {
                \Illuminate\Support\Facades\Mail::to($notificacion->email)->send(new \App\Mail\BackInStockMail($notificacion->variante));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error manual BackInStock: ' . $e->getMessage());
            }
        }
        
        $notificacion->update(['procesado' => true]);
        return back()->with('success', 'Correo enviado y solicitud marcada como resuelta.');
    }

    public function ventasMensuales()
    {
        $meses = collect(range(1, 6))->map(function ($m) {
            return [
                'mes'   => now()->subMonths(6 - $m)->format('M'),
                'total' => Orden::whereNotIn('estado', ['carrito', 'cancelada'])->whereMonth('created_at', now()->subMonths(6 - $m)->month)->sum('total_amount'),
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
        $categorias = OrdenDetalle::with('variante.producto.categoria')
            ->whereHas('orden', fn($q) => $q->whereNotIn('estado', ['carrito', 'cancelada']))
            ->get()
            ->groupBy(fn($d) => $d->variante->producto->categoria->nombre ?? 'Otros')
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
