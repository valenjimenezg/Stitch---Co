@extends('layouts.admin')

@section('title', 'Productos')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Inventario de Productos</h2>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.export') }}" class="bg-white text-slate-700 px-4 py-2.5 rounded-lg text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-lg">download</span>
            Exportar CSV
        </a>
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add</span> Nueva Variante
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@php
    $lowStockCount = \App\Models\DetalleProducto::where('stock', '<=', 5)->count();
@endphp
@if($lowStockCount > 0)
    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-5 flex items-start gap-4 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute -right-6 -top-6 text-rose-100 opacity-50 transform rotate-12 transition-transform group-hover:rotate-6 pointer-events-none">
            <span class="material-symbols-outlined" style="font-size: 150px; font-variation-settings: 'FILL' 1;">warning</span>
        </div>
        <div class="bg-rose-100 text-rose-600 rounded-full size-12 flex items-center justify-center shrink-0 relative z-10">
            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">error</span>
        </div>
        <div class="flex-1 relative z-10">
            <h3 class="text-rose-800 font-black text-lg mb-1 tracking-tight">¡Alerta Crítica de Inventario!</h3>
            <p class="text-rose-700 text-sm mb-4 leading-relaxed font-medium">Actualmente mantienes <strong>{{ $lowStockCount }} variante(s)</strong> con nivel de stock bajísimo (5 unidades o menos). Te recomendamos hacer pedido a proveedores urgemente para no comprometer ventas.</p>
            <a href="{{ route('admin.products.restock') }}" target="_blank" class="inline-flex items-center gap-2 bg-rose-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-rose-700 transition-all shadow-md shadow-rose-600/20 active:scale-95">
                <span class="material-symbols-outlined text-[18px]">print</span> 
                Generar Planilla de Reposición
            </a>
        </div>
    </div>
@endif

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Producto</th>
                    <th class="px-6 py-4">Categoría</th>
                    <th class="px-6 py-4">Grosor / Color</th>
                    <th class="px-6 py-4">Precio</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($variantes as $variante)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center overflow-hidden">
                            @if($variante->imagen)
                                <img src="{{ asset($variante->imagen) }}" class="w-full h-full object-cover"/>
                            @else
                                <span class="material-symbols-outlined text-primary text-lg">straighten</span>
                            @endif
                        </div>
                        <span class="font-semibold">{{ $variante->producto->nombre ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $variante->producto->categoria ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-500">
                        {{ $variante->grosor ?? '' }}{{ $variante->grosor && $variante->color ? ' / ' : '' }}{{ $variante->color ?? '' }}
                        @if(!$variante->grosor && !$variante->color) — @endif
                    </td>
                    <td class="px-6 py-4 font-medium">
                        Ref: ${{ number_format($variante->precio, 2) }} <br><span class="text-xs text-slate-400 font-bold uppercase">{{ bs($variante->precio) }}</span>
                        @if($variante->unidad_medida)
                            <span class="text-xs text-slate-400 font-normal">/ {{ strtolower($variante->unidad_medida) }}</span>
                        @endif
                        @if($variante->en_oferta && $variante->descuento_porcentaje > 0)
                            <span class="ml-1 bg-amber-100 text-amber-700 text-[10px] font-bold px-1.5 py-0.5 rounded">-{{ $variante->descuento_porcentaje }}%</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($variante->stock > 5)
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">{{ $variante->stock }}</span>
                        @elseif($variante->stock > 0)
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold uppercase">{{ $variante->stock }}</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Agotado</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $variante->id) }}"
                               class="p-2 text-slate-400 hover:text-primary rounded-lg hover:bg-primary/5 transition-all">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $variante->id) }}"
                                  onsubmit="return confirm('¿Eliminar esta variante?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-all">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        Sin productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($variantes->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $variantes->links() }}
        </div>
    @endif
</div>

@endsection
