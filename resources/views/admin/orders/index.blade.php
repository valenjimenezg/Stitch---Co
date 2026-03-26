@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Gestión de Pedidos</h2>
    <a href="{{ route('admin.orders.export') }}" class="bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-lg">download</span>
        Exportar CSV
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">N° Pedido</th>
                    <th class="px-6 py-4">Cliente</th>
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Pago / Ref</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ventas as $venta)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-primary font-semibold">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold">{{ $venta->user->nombre ?? '—' }} {{ $venta->user->apellido ?? '' }}</div>
                        <div class="text-xs text-slate-400">{{ $venta->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-semibold">Bs. {{ number_format($venta->total_venta, 2) }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-xs uppercase text-slate-600">{{ str_replace('_', ' ', $venta->metodo_pago) }}</div>
                        @if($venta->referencia_pago)
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                Ref: <span class="font-mono text-slate-700">{{ $venta->referencia_pago }}</span>
                            </div>
                        @endif
                        @if($venta->banco_pago)
                            <div class="text-[10px] text-slate-400">
                                {{ $venta->banco_pago }}
                                @if($venta->telefono_pago)
                                    <span>({{ $venta->telefono_pago }})</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $colores = [
                                'pendiente'   => 'bg-amber-100 text-amber-700',
                                'procesando'  => 'bg-blue-100 text-blue-700',
                                'enviado'     => 'bg-indigo-100 text-indigo-700',
                                'entregado'   => 'bg-emerald-100 text-emerald-700',
                                'cancelado'   => 'bg-red-100 text-red-700',
                            ];
                            $color = $colores[$venta->estado] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $color }}">
                            {{ $venta->estado ?? 'pendiente' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.orders.status', $venta->id) }}" class="flex items-center justify-end gap-2">
                            @csrf @method('PATCH')
                            <select name="estado" onchange="this.form.submit()" class="border-slate-200 rounded-lg text-xs py-1.5 pr-8 focus:ring-primary focus:border-primary cursor-pointer bg-slate-50 hover:bg-white transition-colors">
                                @foreach(['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'] as $estado)
                                    <option value="{{ $estado }}" {{ $venta->estado === $estado ? 'selected' : '' }}>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.orders.show', $venta->id) }}" class="text-slate-500 hover:text-primary p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors bg-white shadow-sm flex items-center justify-center max-h-[34px]" title="Inspeccionar Orden">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        No hay pedidos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ventas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $ventas->links() }}
        </div>
    @endif
</div>

@endsection
