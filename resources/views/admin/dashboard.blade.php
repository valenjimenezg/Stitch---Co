@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<section>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Dashboard Overview</h2>
            @if(Cache::has('bcv_rate'))
                <p class="text-sm text-slate-500 mt-1">
                    Tasa BCV oficial: <strong class="text-slate-900">Bs. {{ Cache::get('bcv_rate') }}</strong> 
                    <span class="text-xs ml-2">(Sincronizada: {{ \Carbon\Carbon::parse(Cache::get('bcv_last_update'))->format('d/m/Y h:i A') }})</span>
                </p>
            @endif
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.settings.bcv') }}" class="px-4 py-2 text-sm font-bold bg-primary text-white hover:bg-primary-dark rounded-lg shadow-sm flex items-center gap-2 transition-colors" title="Configurar Tasa BCV Oficial/Manual">
                <span class="material-symbols-outlined text-lg">currency_exchange</span>
                Configurar Tasa
            </a>
        </div>
    </div>

    {{-- Métricas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.metric-card
            icon="payments"
            label="Ventas Totales"
            :value="'Bs. ' . number_format($totalVentas ?? 0, 2)"
            trend="12.5%"
            :trendUp="true"
        />
        <x-admin.metric-card
            icon="local_mall"
            label="Pedidos del Mes"
            :value="$pedidosMes ?? 0"
            trend="2.1%"
            :trendUp="false"
            iconColor="blue-500"
        />
        <x-admin.metric-card
            icon="inventory"
            label="Stock Total"
            :value="($stockTotal ?? 0) . ' unidades'"
            trend="5.0%"
            :trendUp="true"
            iconColor="amber-500"
        />
        <div class="bg-white border border-red-200 p-5 rounded-2xl shadow-sm flex flex-col justify-between h-full">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-red-100 text-red-500 shrink-0">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-500 mb-1">Alertas Stock</h3>
                    <p class="text-2xl font-black text-slate-900 leading-none">{{ $stockBajo ?? 0 }}</p>
                </div>
            </div>
            <a href="{{ route('admin.products.restock') }}" target="_blank" class="mt-4 flex items-center justify-center w-full py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold gap-2">
                <span class="material-symbols-outlined text-[16px]">print</span>
                Imprimir PDF
            </a>
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
                    @php /** @var \App\Models\DetalleVenta $item */ @endphp
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
                            @if(($item->variante?->stock ?? 0) > 5)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">En Stock</span>
                            @elseif(($item->variante?->stock ?? 0) > 0)
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
