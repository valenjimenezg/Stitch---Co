@extends('layouts.app')

@section('title', $displayName . ' — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<div class="flex flex-wrap items-center gap-2 mb-8">
    <a class="text-slate-400 hover:text-primary text-sm font-medium flex items-center gap-1" href="{{ route('home') }}">
        <span class="material-symbols-outlined text-sm">home</span> Inicio
    </a>
    <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
    <span class="text-primary text-sm font-semibold">{{ $displayName }}</span>
</div>

{{-- Page Header --}}
<div class="flex flex-col gap-4 mb-10">
    <h1 class="text-5xl font-black text-slate-900 leading-tight tracking-tight">{{ $displayName }}</h1>
    <p class="text-slate-600 text-lg max-w-2xl leading-relaxed">
        Encuentra todos los materiales e insumos de {{ strtolower($slug) }} de alta calidad que necesitas para cada puntada de tu proyecto.
    </p>
</div>



{{-- Product Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
    @forelse($variantes ?? [] as $variante)
        <x-product-card :variante="$variante"/>
    @empty
        <div class="col-span-4 text-center text-slate-400 py-20">
            <span class="material-symbols-outlined text-5xl mb-2 block">inventory_2</span>
            No hay productos en esta categoría aún.
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if(isset($variantes) && $variantes->hasPages())
    <div class="flex items-center justify-center gap-2 pb-10">
        {{ $variantes->links() }}
    </div>
@endif

@endsection
