@extends('layouts.admin')

@section('title', 'Avisos de Stock Requerido')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Avisos de Stock</h2>
        <p class="text-sm text-slate-500 mt-1">Gestión de usuarios esperando reabastecimiento de inventario.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.stock-notifications.preview') }}" target="_blank" class="bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2 shadow-sm shrink-0">
            <span class="material-symbols-outlined text-[18px]">visibility</span>
            HTML
        </a>
        <form action="{{ route('admin.stock-notifications.send-test') }}" method="POST" class="flex items-center m-0">
            @csrf
            <input type="email" name="email_prueba" placeholder="correo@ejemplo.com" required class="border border-slate-200 border-r-0 rounded-l-xl px-3 py-2 text-sm w-44 focus:ring-0 focus:border-slate-300">
            <button type="submit" class="bg-primary text-white px-3 py-2 text-sm font-bold border border-primary rounded-r-xl hover:bg-primary-dark transition-all flex items-center gap-1 shadow-sm shrink-0">
                <span class="material-symbols-outlined text-[16px]">send</span> Test
            </button>
        </form>
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
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                @if($alerta->variante && $alerta->variante->imagen)
                                    <img src="{{ asset($alerta->variante->imagen) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <span class="material-symbols-outlined text-[20px]">image</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if($alerta->variante && $alerta->variante->producto)
                                    <a href="{{ route('admin.products.edit', $alerta->variante->id) }}" class="font-bold text-slate-900 hover:text-primary transition-colors flex items-center gap-1" title="Ir a reabastecer inventario">
                                        {{ $alerta->variante->producto->nombre }}
                                        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        Variante: <span class="font-semibold text-slate-700">{{ $alerta->variante->color ?? $alerta->variante->talla ?? 'Único' }}</span>
                                        @if($alerta->variante->grosor) | {{ $alerta->variante->grosor }} @endif
                                    </div>
                                @else
                                    <div class="font-bold text-slate-400 line-through">Producto Eliminado</div>
                                @endif
                            </div>
                        </div>
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
