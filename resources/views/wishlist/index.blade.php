@extends('layouts.app')

@section('title', 'Mi Lista de Deseos — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a class="hover:text-primary" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="font-medium text-slate-900">Mi Lista de Deseos</span>
</nav>

{{-- Page Header --}}
<div class="mb-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
    <div>
        <h2 class="text-4xl font-black tracking-tight text-slate-900">Mi Lista de Deseos</h2>
        <p class="mt-2 text-slate-600">
            Tienes <span class="font-bold text-primary">{{ $items->total() }}</span> artículos guardados para tu próximo proyecto.
        </p>
    </div>
    @if($items->isNotEmpty())
    <div class="flex flex-wrap gap-3">
        <form method="POST" action="{{ route('wishlist.toggle') }}" onsubmit="confirmDeletion(event, '¿Vaciar lista?', 'Todos los productos serán eliminados.')">
            @csrf
            <input type="hidden" name="clear_all" value="1">
            <button class="flex items-center gap-2 rounded-lg border border-primary/20 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-primary/5">
                <span class="material-symbols-outlined text-lg">delete_sweep</span>
                Limpiar lista
            </button>
        </form>
    </div>
    @endif
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

{{-- Grid --}}
@if($items->isNotEmpty())
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @foreach($items as $deseo)
    @php $variante = $deseo->variante; @endphp
    <div class="group relative flex flex-col overflow-hidden rounded-xl border border-primary/5 bg-white">

        {{-- Image --}}
        <div class="relative aspect-square w-full overflow-hidden bg-slate-100">
            <a href="{{ route('products.show', $variante->id) }}" class="block h-full w-full group/link">
            @if($variante->imagen)
                <img src="{{ asset($variante->imagen) }}" alt="{{ $variante->producto->nombre ?? '' }}"
                     class="h-full w-full object-cover transition-transform duration-300 group-hover/link:scale-105"/>
            @else
                <div class="h-full w-full flex items-center justify-center bg-primary/5">
                    <span class="material-symbols-outlined text-5xl text-primary/20">straighten</span>
                </div>
            @endif
            </a>

            {{-- Remove from wishlist --}}
            <form method="POST" action="{{ route('wishlist.toggle') }}" class="absolute right-3 top-3" onsubmit="confirmDeletion(event, '¿Quitar de la lista?', 'El producto será eliminado de tus deseos.')">
                @csrf
                <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                <button class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-sm hover:bg-white">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' 1">favorite</span>
                </button>
            </form>
        </div>

        {{-- Info --}}
        <div class="flex flex-1 flex-col p-4">
            <div class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary/70">
                {{ $variante->producto->categoria ?? '—' }}
            </div>
            <h3 class="text-base font-bold text-slate-900">
                <a href="{{ route('products.show', $variante->id) }}" class="hover:text-primary hover:underline transition-colors">
                    {{ $variante->producto->nombre ?? '—' }}
                </a>
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                {{ $variante->color ? 'Color: ' . $variante->color : '' }}
                {{ $variante->grosor ? ' • Grosor: ' . $variante->grosor : '' }}
            </p>
            <div class="mt-auto pt-4">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex flex-col">
                        <span class="text-lg font-black text-slate-900 leading-none">{{ bs($variante->precio_con_descuento) }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ref: ${{ number_format($variante->precio_con_descuento, 2) }}</span>
                    </div>
                    @if($variante->en_stock)
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">EN STOCK</span>
                    @elseif($variante->stock > 0)
                        <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-bold text-orange-700">POCO STOCK</span>
                    @else
                        <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-bold text-slate-600">SIN STOCK</span>
                    @endif
                </div>

                @if($variante->stock > 0)
                    <form method="POST" action="{{ route('wishlist.move', $deseo->id) }}">
                        @csrf
                        <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary/10 py-2.5 text-sm font-bold text-primary hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined text-lg">shopping_basket</span>
                            Agregar al carrito
                        </button>
                    </form>
                @else
                    <button disabled class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-100 py-2.5 text-sm font-bold text-slate-400 cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg">notifications</span>
                        Sin stock
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($items->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $items->links() }}
    </div>
@endif

@else
    <div class="text-center py-24">
        <span class="material-symbols-outlined text-7xl text-slate-300 mb-4 block">favorite_border</span>
        <h2 class="text-2xl font-bold text-slate-600 mb-2">Tu lista de deseos está vacía</h2>
        <p class="text-slate-400 mb-8">Guarda los productos que más te gustan para comprarlos después.</p>
        <a href="{{ route('home') }}" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all">
            Explorar productos
        </a>
    </div>
@endif

@endsection
