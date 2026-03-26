@extends('layouts.app')

@section('title', 'Ofertas Especiales — Stitch & Co')

@section('content')

{{-- Hero Banner --}}
<div class="mb-8 rounded-2xl overflow-hidden relative h-[260px] flex items-center bg-primary/10">
    <div class="relative z-10 px-12 max-w-2xl">
        <span class="bg-amber-400 text-slate-900 font-bold px-3 py-1 rounded text-xs uppercase tracking-widest mb-4 inline-block">Solo por tiempo limitado</span>
        <h2 class="text-5xl font-extrabold text-slate-900 mb-4">Ofertas Especiales</h2>
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($variantes as $variante)
            <div class="group bg-white rounded-xl overflow-hidden border border-slate-200 transition-all hover:shadow-xl hover:shadow-primary/5">
                <div class="relative h-56 overflow-hidden bg-slate-50">
                    @if($variante->imagen)
                        <img src="{{ asset('storage/' . $variante->imagen) }}" alt="{{ $variante->producto->nombre ?? '' }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-primary/20">straighten</span>
                        </div>
                    @endif
                    @if($variante->en_oferta && $variante->descuento_porcentaje > 0)
                        <div class="absolute top-3 left-3 bg-amber-400 text-slate-900 font-bold px-2 py-1 rounded text-xs">
                            -{{ $variante->descuento_porcentaje }}%
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs text-primary font-bold uppercase tracking-wider mb-1">{{ $variante->producto->categoria ?? '—' }}</p>
                    <h3 class="font-bold text-slate-900 mb-2 leading-tight">{{ $variante->producto->nombre ?? '—' }}</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xl font-bold text-red-500">Bs. {{ number_format($variante->precio_con_descuento, 2) }}</span>
                        @if($variante->en_oferta && $variante->descuento_porcentaje > 0)
                            <span class="text-sm text-slate-400 line-through">Bs. {{ number_format($variante->precio, 2) }}</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                        <input type="hidden" name="cantidad" value="1">
                        <button class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                            Agregar
                        </button>
                    </form>
                </div>
            </div>
            @empty
                <div class="col-span-3 text-center py-20 text-slate-400">
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
