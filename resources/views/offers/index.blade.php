@extends('layouts.app')

@section('title', 'Ofertas Especiales — Stitch & Co')

@section('content')

{{-- Hero Banner --}}
<div class="mb-8 rounded-2xl overflow-hidden relative h-[260px] flex items-center bg-primary/10">
    <div class="relative z-10 px-12 max-w-2xl">
        <span class="bg-amber-400 text-slate-900 font-bold px-3 py-1 rounded text-xs uppercase tracking-widest mb-4 inline-block">Solo por tiempo limitado</span>
        <h2 class="text-5xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
            Ofertas Especiales
            <span class="material-symbols-outlined text-4xl text-black bg-amber-400 rounded-lg p-2" style="font-variation-settings: 'FILL' 1, 'wght' 700;">percent</span>
        </h2>
        <p class="text-lg text-slate-700 mb-6">Hasta 50% de descuento en hilos, kits de costura y telas importadas.</p>
    </div>
</div>

{{-- Breadcrumbs --}}
<div class="flex flex-wrap gap-2 mb-8 text-sm">
    <a class="text-slate-500 hover:text-primary" href="{{ route('home') }}">Inicio</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-medium">Ofertas y Promociones</span>
</div>

<div class="flex flex-col lg:flex-row gap-10">



    {{-- Products --}}
    <div class="flex-1">
        <div class="flex items-center justify-between mb-8">
            <p class="text-sm text-slate-500">
                Mostrando <span class="font-bold text-slate-900">{{ $variantes->total() }}</span> ofertas
            </p>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($variantes as $variante)
                <x-product-card :variante="$variante"/>
            @empty
                <div class="col-span-full text-center py-20 text-slate-400">
                    <span class="material-symbols-outlined text-5xl mb-2 block">sell</span>
                    No hay ofertas disponibles en este momento.
                </div>
            @endforelse
        </div>

        @if($variantes->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $variantes->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
