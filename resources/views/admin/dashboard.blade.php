@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<section>
    {{-- Top Banner - Tasa del Día (Unified with Sidebar Dark Theme) --}}
    <div class="rounded-2xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-start md:items-center justify-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined" style="color: #818cf8;">currency_exchange</span>
                Dashboard de Operaciones
            </h2>
            @if(Cache::has('bcv_rate'))
                <p class="font-medium text-sm" style="color: #cbd5e1;">
                    Tasa BCV del Día: <strong class="text-white text-xl">Bs. {{ Cache::get('bcv_rate') }}</strong> 
                    <span class="text-xs opacity-75 ml-2">(Sincronizada: {{ \Carbon\Carbon::parse(Cache::get('bcv_last_update'))->format('d/m/Y h:i A') }} por Admin)</span>
                </p>
            @endif
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.settings.bcv') }}" class="px-5 py-2.5 text-sm font-bold rounded-xl shadow-sm flex items-center gap-2 transition-all" style="background: rgba(255,255,255,0.1); color: white;">
                Actualizar Tasa BCV
            </a>
        </div>
    </div>

    {{-- Métricas de Acción Inmediata (ERP Action-Driven) --}}
    @php
        // Consultas rápidas (Demo / Real)
        $pagosPendientes = \App\Models\Orden::where('estado', 'pendiente')->count();
        $pedidosPagados = \App\Models\Orden::where('estado', 'pagado')->count(); // Listos para armar
        $pedidosDelivery = \App\Models\Orden::where('tipo_envio', 'delivery')->whereIn('estado', ['pagado', 'en_ruta'])->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Tarjeta 1: Auditoría de Pagos --}}
        <div class="p-5 rounded-2xl shadow-sm flex flex-col justify-between h-full relative overflow-hidden group" style="background: #fffbeb; border: 2px solid #fde68a;">
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm" style="background: #fef3c7; color: #d97706;">
                    <span class="material-symbols-outlined text-2xl">fact_check</span>
                </div>
                <div>
                    <h3 class="text-[13px] font-bold uppercase tracking-wider mb-1" style="color: #b45309;">Auditoría de Pagos</h3>
                    <p class="text-2xl font-black leading-none" style="color: #78350f;">{{ $pagosPendientes }} <span class="text-xs font-semibold" style="color: #d97706;">Por revisar</span></p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}?status=pendiente" class="mt-5 flex items-center justify-center w-full py-2.5 rounded-lg text-xs font-bold gap-2 relative z-10" style="background: #d97706; color: white;">
                Verificar Captures
            </a>
            <div class="absolute -right-6 -bottom-6 opacity-20 transform rotate-12 transition-transform group-hover:scale-110">
                <span class="material-symbols-outlined" style="font-size: 100px;">receipt_long</span>
            </div>
        </div>

        {{-- Tarjeta 2: Preparación Almacén --}}
        <div class="p-5 rounded-2xl shadow-sm flex flex-col justify-between h-full" style="background: #eff6ff; border: 1px solid #bfdbfe;">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: #dbeafe; color: #2563eb;">
                    <span class="material-symbols-outlined text-2xl">inventory_2</span>
                </div>
                <div>
                    <h3 class="text-[13px] font-bold uppercase tracking-wider mb-1" style="color: #2563eb;">Taller / Almacén</h3>
                    <p class="text-2xl font-black text-slate-900 leading-none">{{ $pedidosPagados }} <span class="text-xs font-medium text-slate-500">listos p/armar</span></p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}?status=pagado" class="mt-5 flex items-center justify-center w-full py-2.5 rounded-lg text-xs font-bold gap-2" style="background: #dbeafe; color: #1d4ed8;">
                Ver Órdenes
            </a>
        </div>

        {{-- Tarjeta 3: Logística Delivery --}}
        <div class="p-5 rounded-2xl shadow-sm flex flex-col justify-between h-full" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: #d1fae5; color: #059669;">
                    <span class="material-symbols-outlined text-2xl">two_wheeler</span>
                </div>
                <div>
                    <h3 class="text-[13px] font-bold uppercase tracking-wider mb-1" style="color: #059669;">Rutas Guanare</h3>
                    <p class="text-2xl font-black text-slate-900 leading-none">{{ $pedidosDelivery }} <span class="text-xs font-medium text-slate-500">despachos</span></p>
                </div>
            </div>
            <a href="{{ route('admin.shipping') }}" class="mt-5 flex items-center justify-center w-full py-2.5 rounded-lg text-xs font-bold gap-2" style="background: #d1fae5; color: #047857;">
                Ver Mapa Rutas
            </a>
        </div>

        {{-- Tarjeta 4: Alerta de Stock Crítico --}}
        <div class="bg-white border-2 p-5 rounded-2xl shadow-sm flex flex-col justify-between h-full animate-pulse relative overflow-hidden" style="border-color: #fecaca;">
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: #fee2e2; color: #dc2626;">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <div>
                    <h3 class="text-[13px] font-bold uppercase tracking-wider mb-1" style="color: #ef4444;">Quiebre de Stock</h3>
                    <p class="text-2xl font-black leading-none" style="color: #dc2626;">{{ $stockBajo ?? 0 }} <span class="text-xs font-medium" style="color: #f87171;">ítems críticos</span></p>
                </div>
            </div>
            <div class="mt-5 border-t pt-3" style="border-color: #fee2e2;">
                <a href="{{ route('admin.products.restock') }}" target="_blank" class="flex items-center justify-center w-full py-2 hover:underline text-xs font-bold gap-2 z-10 relative" style="color: #dc2626;">
                    <span class="material-symbols-outlined text-[16px]">contact_phone</span> Contactar Proveedores
                </a>
            </div>
            <div class="absolute right-0 top-0 w-2 h-full" style="background: #ef4444;"></div>
        </div>
    </div>
</section>

{{-- Gráficos --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="font-bold text-lg">Rendimiento de Ventas Mensual</h3>
                <p class="text-slate-400 text-sm">Crecimiento de ingresos en los últimos 6 meses</p>
            </div>
        </div>
        <div class="h-64 w-full relative">
            <canvas id="ventas-chart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col">
        <h3 class="font-bold text-lg mb-2">Distribución por Categoría</h3>
        <p class="text-slate-400 text-sm mb-6">Líneas de productos más vendidas</p>
        <div class="flex-1 flex items-center justify-center">
            <canvas id="categoria-chart" width="180" height="180"></canvas>
        </div>
    </div>
</div>

{{-- Tabla productos más vendidos --}}
<section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
        <h3 class="font-bold text-lg">Productos Más Vendidos</h3>
        <a class="text-primary text-sm font-semibold hover:underline" href="{{ route('admin.products.index') }}">Ver inventario completo</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Producto</th>
                    <th class="px-6 py-4">Categoría</th>
                    <th class="px-6 py-4">Precio</th>
                    <th class="px-6 py-4">Pedidos</th>
                    <th class="px-6 py-4 text-right">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($topProductos ?? [] as $item)
                    @php /** @var \App\Models\OrdenDetalle $item */ @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="size-10 rounded bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-lg">straighten</span>
                            </div>
                            <span class="font-semibold">{{ $item->variante?->producto?->nombre ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $item->variante?->producto?->categoria ?? '—' }}</td>
                        <td class="px-6 py-4 font-medium text-[11px]">
                            Ref: ${{ number_format($item->variante?->precio ?? 0, 2) }} <br><span class="font-bold uppercase">{{ bs($item->variante?->precio ?? 0) }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium">{{ $item->total_pedidos }}</td>
                        <td class="px-6 py-4 text-right">
                            @if(($item->variante?->stock_disponible ?? 0) > 5)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">En Stock</span>
                            @elseif(($item->variante?->stock_disponible ?? 0) > 0)
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold uppercase">Stock Bajo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Sin Stock</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Sin datos disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Charts via admin.js (Chart.js)
    fetch('{{ route('admin.api.ventas-mensuales') }}')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('ventas-chart'), {
                type: 'line',
                data: data,
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }).catch(() => {});

    fetch('{{ route('admin.api.ventas-categoria') }}')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('categoria-chart'), {
                type: 'doughnut',
                data: data,
                options: { responsive: false }
            });
        }).catch(() => {});
</script>
@endpush
