@extends('layouts.admin')

@section('title', 'Avisos de Stock Requerido')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Avisos de Stock</h2>
        <p class="text-sm text-slate-500 mt-1">Gestión de usuarios esperando reabastecimiento de inventario.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Solcitud Nº</th>
                    <th class="px-6 py-4">Correo Electrónico</th>
                    <th class="px-6 py-4">Producto Solicitado</th>
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($notificaciones as $alerta)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-semibold text-slate-600">
                        REQ-{{ str_pad($alerta->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400 text-[18px]">mail</span>
                        <a href="mailto:{{ $alerta->email }}" class="hover:text-primary transition-colors">{{ $alerta->email }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $alerta->variante?->producto?->nombre ?? 'Producto Borrado' }}</div>
                        @if($alerta->variante && $alerta->variante->color)
                            <div class="text-xs text-slate-500 mt-0.5"><span class="font-semibold text-slate-600">Variante:</span> {{ $alerta->variante->color }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500">
                        {{ $alerta->created_at->format('d/m/Y h:i A') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($alerta->procesado)
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">Procesado</span>
                        @else
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold uppercase">En Espera</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if(!$alerta->procesado)
                        <form method="POST" action="{{ route('admin.stock-notifications.update', $alerta->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-primary/10 text-primary hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" title="Marcar como enviado/procesado">
                                Marcar Listo
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">inbox</span>
                        No tienes alertas de stock registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($notificaciones->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>

@endsection
