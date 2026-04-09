@extends('layouts.app')

@section('title', $q ? 'Búsqueda: ' . $q . ' — Stitch & Co' : 'Búsqueda — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<div class="flex flex-wrap items-center gap-2 mb-8 mt-4">
    <a class="text-slate-400 hover:text-primary text-xs font-medium flex items-center gap-1" href="{{ route('home') }}">
        <span class="material-symbols-outlined text-sm">home</span> Inicio
    </a>
    <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
    <span class="text-slate-600 text-xs font-semibold uppercase tracking-wider">Búsqueda</span>
    @if($q)
        <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
        <span class="text-primary text-xs font-bold uppercase tracking-wider">{{ $q }}</span>
    @endif
</div>

<div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

    {{-- SIDEBAR: Filtros --}}
    <aside class="w-full lg:w-72 shrink-0">
        <h1 class="text-2xl font-black text-slate-900 leading-tight tracking-tight mb-1">{{ $q ? ucfirst($q) : 'Catálogo General' }}</h1>
        <p class="text-sm text-slate-500 font-medium mb-8">{{ $variantes->total() }} resultados detectados</p>


        
        <div class="mb-8">
            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide mb-4">Etiquetas Activas</h3>
            <div class="flex flex-wrap gap-2">
                @if($q)
                <span class="inline-flex items-center text-primary text-xs font-black px-3 py-1.5 bg-primary/5 border border-primary/20 rounded-lg shadow-sm">
                    {{ $q }}
                </span>
                @endif

            </div>
        </div>
        
        {{-- Banner de envios informativo lateral estilo ML --}}
        <div class="bg-blue-50 border border-blue-200 p-5 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="absolute -right-2 -bottom-2 opacity-5 pointer-events-none">
                <span class="material-symbols-outlined text-8xl text-blue-900">local_shipping</span>
            </div>
            <div class="relative z-10">
                <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center mb-3 shadow-md">
                    <span class="material-symbols-outlined font-light">local_shipping</span>
                </div>
                <h4 class="text-blue-900 font-black text-sm mb-1 leading-tight">Envíos rápidos en tu localidad</h4>
                <p class="text-xs text-blue-800/80 font-medium leading-relaxed">Solo despachamos al <strong>casco urbano de Guanare, Portuguesa</strong>.</p>
            </div>
        </div>
    </aside>

    {{-- MAIN: Resultados --}}
    <main class="flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @forelse($variantes as $variante)
                <x-product-card :variante="$variante"/>
            @empty
                <div class="col-span-full py-24 bg-white rounded-3xl border border-slate-100 flex flex-col items-center justify-center text-center shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 flex items-center justify-center rounded-full mb-6 border-2 border-slate-100 border-dashed">
                        <span class="material-symbols-outlined text-4xl text-slate-400 block mx-auto">search_off</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">No hay publicaciones que coincidan con tu búsqueda.</h3>
                    <div class="text-sm text-slate-500 text-left mt-2">
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-slate-300">check_circle</span> 
                                <strong>Revisa la ortografía</strong> de la palabra.
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-slate-300">check_circle</span> 
                                Utiliza <strong>palabras más genéricas</strong> o sinónimos.
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-slate-300">check_circle</span> 
                                Navega por las categorías principales desde el menú superior.
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('home') }}" class="mt-8 bg-slate-900 text-white font-bold py-3 px-8 rounded-xl hover:bg-slate-800 transition-colors shadow-lg">
                        Volver al inicio
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($variantes->hasPages())
            <div class="flex items-center justify-center pb-12">
                {{ $variantes->onEachSide(1)->links() }}
            </div>
        @endif
    </main>

</div>

@endsection
