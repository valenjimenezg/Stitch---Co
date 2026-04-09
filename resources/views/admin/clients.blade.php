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
