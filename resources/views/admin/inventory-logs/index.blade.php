@extends('layouts.admin')

@section('title', 'Kardex - Movimientos de Inventario')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Historial de Inventario (Kardex)</h2>
        <p class="text-slate-500 text-sm mt-1">Monitorea y audita cada entrada o salida de stock</p>
    </div>
    
    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg p-1 shadow-sm">
        <a href="{{ route('admin.inventario-logs') }}" 
           class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all {{ !request('tipo') ? 'bg-slate-100 text-slate-800' : 'text-slate-500 hover:bg-slate-50' }}">
            Todos
        </a>
        <a href="{{ route('admin.inventario-logs', ['tipo' => 'entrada']) }}" 
           class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all {{ request('tipo') == 'entrada' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50' }}">
            Entradas
        </a>
        <a href="{{ route('admin.inventario-logs', ['tipo' => 'salida']) }}" 
           class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all {{ request('tipo') == 'salida' ? 'bg-rose-50 text-rose-700' : 'text-slate-500 hover:bg-slate-50' }}">
            Salidas
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Tipo</th>
                    <th class="px-6 py-4">Producto / Variante</th>
                    <th class="px-6 py-4 text-center">Cantidad</th>
                    <th class="px-6 py-4">Motivo / Origen</th>
                    <th class="px-6 py-4">Responsable</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-slate-900">{{ $log->created_at->format('d M, Y') }}</div>
                        <div class="text-xs text-slate-400">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->cantidad_cambio > 0)
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold uppercase">
                                <span class="material-symbols-outlined text-[14px]">arrow_downward</span> Entrada
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-rose-100 text-rose-700 px-2 py-1 rounded text-xs font-bold uppercase">
                                <span class="material-symbols-outlined text-[14px]">arrow_upward</span> Salida
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">
                            {{ $log->variante->producto->nombre ?? 'Producto Eliminado' }}
                            @if($log->variante && ($log->variante->color || $log->variante->grosor))
                                <span class="text-xs font-normal text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full ml-1">
                                    {{ $log->variante->color }} {{ $log->variante->grosor }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-primary font-medium mt-0.5">
                            SKU: VAR-{{ str_pad($log->variante_id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-base font-black {{ $log->cantidad_cambio > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $log->cantidad_cambio > 0 ? '+' : '' }}{{ rtrim(rtrim((string)$log->cantidad_cambio, '0'), '.') }}
                        </span>
                        <span class="text-xs text-slate-400 ml-1">{{ strtolower($log->variante->unidad_medida ?? 'u') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-700 max-w-xs truncate" title="{{ $log->motivo }}">
                            {{ $log->motivo }}
                        </div>
                        @if($log->orden_id)
                            <a href="{{ route('admin.orders.show', $log->orden_id) }}" class="text-xs font-semibold text-primary hover:underline mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">receipt_long</span> 
                                Ver Pedido #{{ $log->orden_id }}
                            </a>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 font-medium">
                        @if($log->user)
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">person</span>
                                {{ $log->user->nombre }}
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">smart_toy</span>
                                Sistema ERP
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-5xl text-slate-300 mb-3 block">history_edu</span>
                        <h4 class="text-lg font-bold text-slate-700 mb-1">Sin movimientos recientes</h4>
                        <p class="text-slate-500 text-sm">El historial de inventario (kardex) está vacío por los momentos.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection
