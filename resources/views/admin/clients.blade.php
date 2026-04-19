@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Clientes Registrados</h2>
    <a href="{{ route('admin.clients.export') }}" class="bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-lg">download</span>
        Exportar CSV
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Cliente</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Teléfono</th>
                    <th class="px-6 py-4">Registrado</th>
                    <th class="px-6 py-4">Estado / Etiquetas</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                            {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                            @if($cliente->document_number)
                                <div class="text-xs text-slate-400">CI: {{ $cliente->document_type }}{{ $cliente->document_number }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $cliente->email }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $cliente->telefono ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $cliente->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5 items-start max-w-[200px]">
                            @if($cliente->is_active)
                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase px-2 py-0.5 rounded border border-emerald-100 flex items-center gap-1 shrink-0" title="Cliente Activo (Con Compras)">
                                    <span class="material-symbols-outlined text-[12px]">shopping_bag</span> Activo
                                </span>
                            @else
                                <span class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase px-2 py-0.5 rounded border border-slate-200 flex items-center gap-1 shrink-0" title="Sin Compras Recientes">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span> Inactivo
                                </span>
                            @endif

                            @if($cliente->is_community)
                                <span class="bg-purple-50 text-purple-600 text-[10px] font-bold uppercase px-2 py-0.5 rounded border border-purple-100 flex items-center gap-1 shrink-0" title="Suscrito al Newsletter / Comunidad">
                                    <span class="material-symbols-outlined text-[12px]">mark_email_read</span> Comunidad
                                </span>
                            @endif

                            @if($cliente->is_stock_wait)
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold uppercase px-2 py-0.5 rounded border border-blue-100 flex items-center gap-1 shrink-0" title="A la espera de la reposición de algún artículo">
                                    <span class="material-symbols-outlined text-[12px]">notifications_active</span> Reposición
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.clients.show', $cliente->id) }}" class="text-primary hover:text-primary-dark font-semibold text-sm flex items-center gap-1 justify-end">
                            <span class="material-symbols-outlined text-[18px]">visibility</span> Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        No hay clientes registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clientes->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $clientes->links() }}
        </div>
    @endif
</div>

@endsection
