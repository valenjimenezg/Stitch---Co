@extends('layouts.admin')

@section('title', 'Logística y Despachos')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Logística de Despachos</h2>
        <p class="text-sm text-slate-500 mt-1">Gestión de empaquetado y emisión de guías de envío físicas.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-400 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Orden</th>
                    <th class="px-6 py-4">Destinatario</th>
                    <th class="px-6 py-4">Dirección de Entrega</th>
                    <th class="px-6 py-4">Operador</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($envios as $envio)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-5">
                        <div class="font-mono font-bold text-slate-800 text-base">#{{ str_pad($envio->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $envio->detalles->sum('cantidad') }} artículos</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-semibold text-slate-700 flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-400 text-lg">person</span>
                            {{ $envio->user->nombre ?? 'Invitado' }} {{ $envio->user->apellido ?? '' }}
                        </div>
                    </td>
                    <td class="px-6 py-5 max-w-xs">
                        @if($envio->calle_envio)
                            <div class="font-medium text-slate-800 truncate" title="{{ $envio->calle_envio }}">{{ $envio->calle_envio }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $envio->ciudad_envio }}, {{ $envio->estado_envio }} (CP: {{ $envio->codigo_postal_envio }})</div>
                        @else
                            <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs">
                                <span class="material-symbols-outlined text-[14px] mr-1">store</span> Retiro en Tienda
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <span class="font-bold text-slate-700">{{ strtoupper($envio->agencia_envio ?? 'STITCH&CO') }}</span>
                    </td>
                    <td class="px-6 py-5">
                        @if($envio->estado == 'procesando')
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Empaquetando</span>
                        @else
                            <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">En Ruta</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('admin.orders.show', $envio->id) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Imprimir Guía / Detalles">
                            <span class="material-symbols-outlined text-xl">print</span>
                        </a>
                        @if($envio->estado == 'procesando')
                        <form action="{{ route('admin.orders.status', $envio->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="estado" value="enviado">
                            <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white hover:bg-primary rounded-lg text-xs font-bold transition-colors shadow-sm">
                                Marcar Enviado
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-300 block mb-3">inventory_2</span>
                        <p class="text-slate-500 font-medium text-base">No hay cajas en cola de empaquetado.</p>
                        <p class="text-slate-400 text-sm mt-1">Los pedidos aprobados aparecerán aquí cuando deban ser procesados para despacho físico.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($envios->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $envios->links() }}
        </div>
    @endif
</div>

@endsection
