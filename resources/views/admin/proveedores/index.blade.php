@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Directorio de Proveedores</h2>
        <p class="text-slate-500 text-sm mt-1">Gestión de contactos de los fabricantes y distribuidores de material.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Proveedor / Empresa</th>
                    <th class="px-6 py-4">Contacto Principal</th>
                    <th class="px-6 py-4">Dirección</th>
                    <th class="px-6 py-4 text-center">Variantes Surtidas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($proveedores as $proveedor)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 border border-orange-200">
                            <span class="material-symbols-outlined text-xl">factory</span>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">{{ $proveedor->nombre }}</div>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 tracking-wider">{{ $proveedor->tipo_documento }}-{{ $proveedor->documento_identidad }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            @if($proveedor->email)
                            <a href="mailto:{{ $proveedor->email }}" class="inline-flex items-center gap-1.5 text-xs text-primary hover:underline font-medium">
                                <span class="material-symbols-outlined text-[14px]">mail</span> {{ $proveedor->email }}
                            </a>
                            @endif
                            @if($proveedor->telefono)
                            <a href="tel:{{ $proveedor->telefono }}" class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-slate-900 font-medium">
                                <span class="material-symbols-outlined text-[14px]">call</span> {{ $proveedor->telefono }}
                            </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 leading-tight">
                        {{ $proveedor->direccion ?? 'Sin dirección registrada' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center bg-slate-100 text-slate-700 font-black text-xs px-3 py-1 rounded-lg border border-slate-200">
                            {{ $proveedor->producto_variantes_count }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        <div class="size-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-3xl text-slate-300">factory</span>
                        </div>
                        <p class="font-medium">No hay proveedores registrados en el sistema.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proveedores->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $proveedores->links() }}
        </div>
    @endif
</div>

@endsection
