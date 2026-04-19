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
                        <div class="flex items-center gap-2">
                            @if($proveedor->telefono)
                                @php
                                    $phoneFormat = preg_replace('/[^0-9]/', '', $proveedor->telefono);
                                    if(str_starts_with($phoneFormat, '0')) $phoneFormat = '58' . substr($phoneFormat, 1);
                                    $msg = urlencode("Hola {$proveedor->nombre}, te saluda el departamento de Compras de Stitch & Co. Te contacto para ");
                                @endphp
                                <a href="https://wa.me/{{ $phoneFormat }}?text={{ $msg }}" target="_blank" title="Enviar WhatsApp a {{ $proveedor->telefono }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Info</span>
                                </a>
                            @endif

                            @if($proveedor->email)
                                @php
                                    $emailSubject = rawurlencode("Revisión de Inventario / Catálogo - Stitch & Co.");
                                    $emailBody = rawurlencode("Estimado proveedor {$proveedor->nombre},\n\nNos dirigimos a usted desde Administración para...\n\nAtentamente,\nCompras Stitch & Co.");
                                @endphp
                                <a href="mailto:{{ $proveedor->email }}?subject={{ $emailSubject }}&body={{ $emailBody }}" title="Enviar Correo a {{ $proveedor->email }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-200 text-orange-700 hover:bg-orange-100 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">mail</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Mail</span>
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
