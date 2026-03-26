@extends('layouts.admin')
@section('title', 'Notificaciones de Stock')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Alertas de Stock</h2>
        <p class="text-slate-500 text-sm">Clientes esperando reposición de productos</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50/80 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <th class="px-8 py-4">Cliente (Email)</th>
                    <th class="px-8 py-4">Producto Esperado</th>
                    <th class="px-8 py-4 text-center">Estado</th>
                    <th class="px-8 py-4 text-right">Fecha Solicitud</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($notificaciones as $notif)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2 text-slate-900 font-semibold">
                            <span class="material-symbols-outlined text-slate-400">mail</span>
                            {{ $notif->email }}
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <div class="font-bold text-slate-900">{{ $notif->variante->producto->nombre ?? 'Producto Eliminado' }}</div>
                        <div class="text-[10px] text-slate-500 uppercase mt-0.5">
                            {{ $notif->variante->color ?? '' }} {{ $notif->variante->grosor ?? '' }}
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($notif->procesado)
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase rounded-lg">Notificado</span>
                        @else
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase rounded-lg">Pendiente</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right text-slate-600 font-medium">
                        {{ $notif->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <span class="material-symbols-outlined text-5xl text-slate-200">notifications_off</span>
                            <p class="text-slate-400 italic">No hay solicitudes de notificación de stock.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notificaciones->hasPages())
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/30">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>
@endsection
