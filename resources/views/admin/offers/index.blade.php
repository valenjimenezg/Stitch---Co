@extends('layouts.admin')

@section('title', 'Gestión de Promociones')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Gestor de Promociones</h2>
        <p class="text-sm text-slate-500 mt-1">Aplica descuentos en masa en milisegundos mediante selecciones en lote.</p>
    </div>
</div>

<form action="{{ route('admin.offers.apply') }}" method="POST">
    @csrf

    {{-- Toolbar Flotante de Acciones en Lote --}}
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4 sticky top-6 z-10 animate-fade-in">
        <div class="flex items-center gap-3">
            <input type="checkbox" id="selectAll" class="size-4 text-primary focus:ring-primary border-slate-300 rounded cursor-pointer ml-2">
            <label for="selectAll" class="text-sm font-bold text-slate-700 cursor-pointer">Seleccionar Todos</label>
            <span class="w-px h-6 bg-slate-200 mx-2"></span>
            
            <form method="GET" action="{{ route('admin.offers.index') }}" class="flex items-center" id="searchForm">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar línea de producto..." class="pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/50 outline-none w-64" onchange="document.getElementById('searchForm').submit();">
                </div>
            </form>
        </div>

        <div class="flex items-center gap-3 border-l border-slate-100 pl-4 ml-auto">
            <div class="flex items-center bg-slate-50 rounded-lg border border-slate-200 overflow-hidden">
                <span class="pl-3 pr-2 py-2 text-slate-400 font-bold bg-slate-100 border-r border-slate-200">-</span>
                <input type="number" name="descuento" placeholder="Ej. 15" min="1" max="99" class="w-20 px-3 py-2 border-none outline-none text-sm font-bold bg-transparent text-slate-700 focus:ring-0">
                <span class="pr-3 pl-2 py-2 text-slate-400 font-bold bg-slate-100 border-l border-slate-200">%</span>
            </div>
            
            <button type="submit" name="accion" value="activar" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">sell</span>
                Lanzar Oferta
            </button>
            
            <button type="submit" name="accion" value="desactivar" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-5 py-2 rounded-lg text-sm font-bold transition-colors shadow-sm" onclick="return confirm('¿Seguro quieres retirar la oferta de los productos seleccionados?')">
                Retirar Ofertas
            </button>
        </div>
    </div>

    {{-- Tabla de Inventario --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                        <th class="px-6 py-4 w-10"></th>
                        <th class="px-6 py-4">Producto</th>
                        <th class="px-6 py-4">Categoría / Variante</th>
                        <th class="px-6 py-4">Precio Base</th>
                        <th class="px-6 py-4">Estado Actual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($variantes as $v)
                    <tr class="hover:bg-slate-50 transition-colors {{ $v->en_oferta ? 'bg-amber-50/20' : '' }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="variantes[]" value="{{ $v->id }}" class="variant-checkbox size-4 text-primary focus:ring-primary border-slate-300 rounded cursor-pointer">
                        </td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="size-10 rounded shadow-sm bg-white border border-slate-200 overflow-hidden flex items-center justify-center">
                                @if($v->imagen)
                                    <img src="{{ asset('storage/' . $v->imagen) }}" class="w-full h-full object-cover"/>
                                @else
                                    <span class="material-symbols-outlined text-slate-300">image</span>
                                @endif
                            </div>
                            <span class="font-bold text-slate-800">{{ $v->producto->nombre ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-900 font-medium">{{ $v->producto->categoria ?? 'General' }}</div>
                            <div class="text-xs text-slate-500">{{ $v->color ?? '' }} {{ $v->grosor ? '('.$v->grosor.')' : '' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono font-medium text-slate-600">
                            Bs. {{ number_format($v->precio, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($v->en_oferta)
                                <div class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-bold gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">local_fire_department</span>
                                    -{{ $v->descuento_porcentaje }}% OFF
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Precio Regular</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            Ningún producto coincide con la búsqueda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($variantes->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $variantes->links() }}
            </div>
        @endif
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        document.querySelectorAll('.variant-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endpush
