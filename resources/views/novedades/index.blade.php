@extends('layouts.app')

@section('title', 'Novedades — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a class="hover:text-primary" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="font-medium text-slate-900">Novedades</span>
</nav>

{{-- Page Header --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-2">
        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">Recién Llegados</span>
    </div>
    <h2 class="text-4xl font-black tracking-tight text-slate-900">Novedades</h2>
    <p class="mt-2 text-slate-600 max-w-2xl">
        Descubre las últimas joyas que han llegado a nuestro catálogo. Desde hilos premium hasta las telas más exclusivas para tus próximos proyectos.
    </p>
</div>

{{-- Grid --}}
@if($variantes->isNotEmpty())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($variantes as $v)
        <x-product-card :variante="$v"/>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-16 flex justify-center">
    {{ $variantes->links() }}
</div>

@else
    <div class="text-center py-24 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
        <span class="material-symbols-outlined text-7xl text-slate-300 mb-4 block">new_releases</span>
        <h2 class="text-2xl font-bold text-slate-600 mb-2">Actualizando catálogo...</h2>
        <p class="text-slate-400 mb-8">Estamos preparando nuevas sorpresas para ti. Vuelve pronto.</p>
        <a href="{{ route('home') }}" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all">
            Ver destacados
        </a>
    </div>
@endif

@endsection
