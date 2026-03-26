@extends('layouts.app')

@section('title', 'Catálogo de Productos — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a class="hover:text-primary" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="font-medium text-slate-900">{{ $q ? 'Búsqueda' : 'Catálogo' }}</span>
</nav>

{{-- Page Header --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-2">
        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">{{ $variantes->total() }} {{ $variantes->total() === 1 ? 'Producto' : 'Productos' }}</span>
    </div>
    <h2 class="text-4xl font-black tracking-tight text-slate-900">
        {{ $q ? 'Resultados' : 'Catálogo' }}
    </h2>
    <p class="mt-2 text-slate-600 max-w-2xl">
        @if($q)
            Buscando: <span class="bg-slate-100 px-2 py-0.5 rounded text-slate-900 font-bold">"{{ $q }}"</span>
        @else
            Explora nuestra colección curada de insumos premium para tus proyectos de costura y manualidades.
        @endif
    </p>
</div>

{{-- Results Section --}}
<div class="flex flex-col mb-20 relative z-10">
    {{-- Top Bar --}}
    @if($variantes->isNotEmpty())
    <div class="flex items-center justify-between bg-white border border-slate-100/70 p-4 rounded-2xl shadow-sm mb-10 text-sm font-medium text-slate-500">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-xl text-primary">filter_list</span>
            Mostrando {{ $variantes->firstItem() ?? 0 }} - {{ $variantes->lastItem() ?? 0 }} de {{ $variantes->total() }}
        </div>
        <div class="flex gap-4">
            <span class="hidden md:inline-block">Ordenar por: <strong class="text-slate-900 border-b border-primary border-dashed pb-0.5 cursor-pointer">Relevancia</strong></span>
        </div>
    </div>
    @endif

    {{-- Grid --}}
    @if($variantes->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($variantes as $v)
                <x-product-card :variante="$v"/>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($variantes->hasPages())
        <div class="mt-16 flex justify-center">
            {{ $variantes->links() }}
        </div>
        @endif

    @else
        {{-- Empty State --}}
        <div class="text-center py-24 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
            <span class="material-symbols-outlined text-7xl text-slate-300 mb-4 block">search_off</span>
            <h2 class="text-2xl font-bold text-slate-600 mb-2">No encontramos coincidencias</h2>
            <p class="text-slate-400 mb-8">No pudimos encontrar ningún artículo que coincida con tu búsqueda. Intenta con otros términos.</p>
            <a href="{{ route('search.index') }}" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all">
                Ver todos los productos
            </a>
        </div>
    @endif
</div>

@endsection
