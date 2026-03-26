@extends('layouts.app')

@section('title', ($variante->producto->nombre ?? 'Producto') . ' — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="flex items-center gap-2 text-sm text-slate-400 mb-10">
    <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <a class="hover:text-primary transition-colors" href="{{ route('categories.show', $variante->producto->categoria ?? 'all') }}">{{ $variante->producto->categoria ?? 'Categoría' }}</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-slate-900 font-semibold">{{ $variante->producto->nombre ?? '—' }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

    {{-- Image Gallery --}}
    <div class="space-y-6">
        <div id="image-zoom-container" class="aspect-square w-full rounded-2xl overflow-hidden bg-white border border-slate-100 shadow-sm relative group cursor-zoom-in">
            @if($variante->imagen)
                <img id="product-image" src="{{ asset('storage/' . $variante->imagen) }}" alt="{{ $variante->producto->nombre ?? '' }}" class="w-full h-full object-cover transition-transform duration-100 ease-out origin-center"/>
            @else
                <div id="product-image" class="w-full h-full flex items-center justify-center bg-primary/5 transition-transform duration-100 ease-out origin-center">
                    <span class="material-symbols-outlined text-6xl text-primary/30">straighten</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Product Info --}}
    <div class="flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1.5 rounded-full tracking-wider uppercase">
                {{ $variante->producto->categoria ?? 'Producto' }}
            </span>
            
            @php
                $cat = strtolower(trim($variante->producto->categoria ?? ''));
                $unidad = '';
                if (in_array($cat, ['telas', 'tela'])) {
                    $unidad = '';
                } elseif (in_array($cat, ['hilos', 'hilo', 'lanas', 'lana', 'estambres'])) {
                    $unidad = $variante->grosor ? 'Grosor: ' . $variante->grosor : ($variante->cm ? $variante->cm . 'cm' : 'Se vende por unidad');
                } elseif (in_array($cat, ['kits', 'kit'])) {
                    $unidad = 'Kit Set Completo';
                }
            @endphp
            
            @if($unidad)
                <span class="text-slate-400 text-xs font-bold px-3 py-1.5 rounded-full border border-slate-200 flex items-center gap-1.5 bg-slate-50">
                    <span class="material-symbols-outlined text-[14px]">info</span> {{ $unidad }}
                </span>
            @endif
        </div>

        <h2 class="text-4xl font-black text-slate-900 mb-3 leading-tight">{{ $variante->producto->nombre ?? '—' }}</h2>

        <div class="flex items-baseline gap-4 mb-8">
            @if($variante->en_oferta && $variante->descuento_porcentaje > 0)
                <span class="text-3xl font-black text-primary">Bs. {{ number_format($variante->precio_con_descuento, 2) }}</span>
                <span class="text-xl text-slate-400 line-through">Bs. {{ number_format($variante->precio, 2) }}</span>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">-{{ $variante->descuento_porcentaje }}%</span>
            @else
                <span class="text-3xl font-black text-primary">Bs. {{ number_format($variante->precio, 2) }}</span>
            @endif

            @if($variante->stock > 0)
                <span class="text-emerald-500 flex items-center gap-1 text-sm font-bold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                    <span class="material-symbols-outlined text-lg">check_circle</span> Disponibles: {{ $variante->stock }} unidades
                </span>
            @else
                <span class="text-rose-500 flex items-center gap-1 text-sm font-black bg-rose-50 px-3 py-1 rounded-full border border-rose-100 uppercase tracking-widest">
                    <span class="material-symbols-outlined text-lg">cancel</span> Agotado
                </span>
            @endif
        </div>

        <p class="text-slate-600 leading-relaxed mb-10 text-lg">
            {{ $variante->producto->descripcion ?? 'Sin descripción disponible.' }}
        </p>

        {{-- Variants (other colors/variants of same product) --}}
        @if($variante->producto->detalleProductos->count() > 1)
        <div class="mb-8">
            <span class="block text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest">
                Variante: <span class="text-slate-500 font-normal">{{ $variante->talla ?? $variante->color ?? '—' }}</span>
            </span>
            <div class="flex gap-3 flex-wrap">
                @foreach($variante->producto->detalleProductos as $v)
                    @php $isOutOfStock = $v->stock <= 0; @endphp
                    <a href="{{ route('products.show', $v->id) }}"
                       class="px-4 py-2 rounded-lg border-2 text-sm font-semibold transition-all relative overflow-hidden
                              {{ $v->id === $variante->id ? 'border-primary bg-primary/10 text-primary' : 'border-slate-200 hover:border-slate-300 text-slate-700' }}
                              {{ $isOutOfStock ? 'opacity-60 bg-slate-50' : 'bg-white' }}">
                        
                        {{ $v->talla ?? $v->color ?? 'Variante ' . $loop->iteration }}
                        
                        @if($isOutOfStock)
                            <span class="absolute block w-full h-[2px] bg-slate-400 top-1/2 left-0 -translate-y-1/2 -rotate-12"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Add to Cart --}}
        @if($variante->stock > 0)
        <form action="{{ route('cart.add') }}" method="POST" class="mb-10">
            @csrf
            <input type="hidden" name="variante_id" value="{{ $variante->id }}">

            <div class="mb-6">
                <span class="block text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest">Cantidad</span>
                <div class="flex items-center gap-6">
                    <div class="flex items-center bg-white rounded-xl p-1.5 border border-slate-200 shadow-sm">
                        <button type="button" onclick="changeQty(-1)" class="size-10 flex items-center justify-center hover:bg-slate-50 rounded-lg transition-colors text-slate-600">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="{{ $variante->stock }}"
                               class="w-14 text-center font-bold text-lg border-none focus:ring-0 bg-transparent">
                        <button type="button" onclick="changeQty(1)" class="size-10 flex items-center justify-center hover:bg-slate-50 rounded-lg transition-colors text-slate-600">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                    <span class="text-slate-400 text-sm italic font-medium">{{ $variante->stock }} unidades disponibles</span>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-bold py-5 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-primary/30">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Agregar al carrito
                </button>

                @php $enWishlist = auth()->check() ? auth()->user()->listaDeseos()->where('variante_id', $variante->id)->exists() : false; @endphp
                <form action="{{ route('wishlist.toggle') }}" method="POST" {!! $enWishlist ? 'onsubmit="confirmDeletion(event, \'¿Quitar de la lista?\', \'El producto será eliminado de tus deseos.\')"' : '' !!}>
                    @csrf
                    <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                    <button type="submit" class="px-6 border-2 rounded-2xl transition-all h-full {{ $enWishlist ? 'border-red-200 bg-red-50 text-red-500 hover:bg-red-100' : 'border-slate-100 hover:border-primary/20 hover:bg-primary/5 text-slate-400 hover:text-primary' }}">
                        <span class="material-symbols-outlined text-3xl" {!! $enWishlist ? 'style="font-variation-settings:\'FILL\' 1"' : '' !!}>favorite</span>
                    </button>
                </form>
            </div>
        </form>
        @else
        <div class="bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl p-6 mb-10 shadow-inner relative overflow-hidden">
            <!-- Decorative accent -->
            <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-400"></div>
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-start gap-3 border border-emerald-200">
                    <span class="material-symbols-outlined mt-0.5">check_circle</span>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @else
                <h4 class="text-lg font-black mb-2 flex items-center gap-2 text-slate-900">
                    <span class="material-symbols-outlined text-slate-500">notifications_active</span> ¡Agotado momentáneamente!
                </h4>
                <p class="text-sm font-medium mb-5 text-slate-600">
                    Si te encanta este color o variante, déjanos tu correo. Te enviaremos un aviso exclusivo en cuanto nuestro inventario sea reabastecido.
                </p>
                <form action="{{ route('stock-notification.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">mail</span>
                        <input type="email" name="email" required placeholder="Tu correo electrónico"
                               class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl pl-12 pr-4 py-3.5 focus:ring-slate-900 focus:border-slate-900 shadow-sm font-medium placeholder-slate-400 outline-none">
                    </div>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-black px-8 py-3.5 rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                        Avisarme <span class="material-symbols-outlined text-sm">send</span>
                    </button>
                </form>
                @error('email')
                    <span class="text-xs text-rose-500 mt-2 block font-bold">{{ $message }}</span>
                @enderror
            @endif
        </div>
        @endif

        {{-- Ficha Técnica --}}
        <div class="mt-10 pt-8 border-t border-slate-100">
            <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">description</span> Ficha Técnica
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                @if($variante->grosor && !in_array(strtolower(trim($variante->producto->categoria ?? '')), ['kits', 'kit', 'telas', 'tela']))
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 text-primary mb-3">
                        <span class="material-symbols-outlined bg-primary/10 p-2 rounded-lg text-sm">straighten</span>
                        <span class="font-black uppercase text-xs tracking-widest">Grosor</span>
                    </div>
                    <p class="text-lg font-semibold text-slate-900">{{ $variante->grosor }}</p>
                </div>
                @endif
                @if($variante->color)
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 text-primary mb-3">
                        <span class="material-symbols-outlined bg-primary/10 p-2 rounded-lg text-sm">palette</span>
                        <span class="font-black uppercase text-xs tracking-widest">Color</span>
                    </div>
                    <p class="text-lg font-semibold text-slate-900">{{ $variante->color }}</p>
                </div>
                @endif
                @if($variante->cm)
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 text-primary mb-3">
                        <span class="material-symbols-outlined bg-primary/10 p-2 rounded-lg text-sm">architecture</span>
                        <span class="font-black uppercase text-xs tracking-widest">Medida / Largo</span>
                    </div>
                    @php
                        $medida = floatval($variante->cm);
                        $textoMedida = $medida . ' cm';
                        if (in_array(strtolower(trim($variante->producto->categoria ?? '')), ['telas', 'tela'])) {
                            if ($medida >= 100) {
                                $metros = $medida / 100;
                                $textoMedida = $metros . ' ' . ($metros == 1 ? 'Metro' : 'Metros');
                            }
                        }
                    @endphp
                    <p class="text-lg font-semibold text-slate-900">{{ $textoMedida }}</p>
                </div>
                @endif
                @if($variante->marca)
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 text-primary mb-3">
                        <span class="material-symbols-outlined bg-primary/10 p-2 rounded-lg text-sm">sell</span>
                        <span class="font-black uppercase text-xs tracking-widest">Marca</span>
                    </div>
                    <p class="text-lg font-semibold text-slate-900">{{ $variante->marca }}</p>
                </div>
                @else
                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 text-primary mb-3">
                        <span class="material-symbols-outlined bg-primary/10 p-2 rounded-lg text-sm">category</span>
                        <span class="font-black uppercase text-xs tracking-widest">Categoría</span>
                    </div>
                    <p class="text-lg font-semibold text-slate-900">{{ $variante->producto->categoria ?? '—' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Related Products --}}
@if($relacionados->isNotEmpty())
<div class="mt-28">
    <div class="flex items-center justify-between mb-12">
        <h3 class="text-3xl font-black text-slate-900">Productos Relacionados</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($relacionados as $rel)
            <x-product-card :variante="$rel"/>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function changeQty(delta) {
        const input = document.getElementById('cantidad');
        const max = parseInt(input.max);
        const newVal = parseInt(input.value) + delta;
        if (newVal >= 1 && newVal <= max) {
            input.value = newVal;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Sephora-style Image Zoom
        const container = document.getElementById('image-zoom-container');
        const img = document.getElementById('product-image');

        if (container && img) {
            const handleZoom = function(clientX, clientY) {
                const rect = container.getBoundingClientRect();
                const x = clientX - rect.left;
                const y = clientY - rect.top;

                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;

                img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                img.style.transform = 'scale(2.5)';
            };

            const resetZoom = function() {
                img.style.transformOrigin = 'center center';
                img.style.transform = 'scale(1)';
            };

            // Desktop
            container.addEventListener('mousemove', function(e) {
                handleZoom(e.clientX, e.clientY);
            });
            container.addEventListener('mouseleave', resetZoom);

            // Mobile
            container.addEventListener('touchmove', function(e) {
                if(e.touches.length > 0) {
                    // Prevent page scroll when zooming on mobile
                    e.preventDefault();
                    handleZoom(e.touches[0].clientX, e.touches[0].clientY);
                }
            }, { passive: false });
            container.addEventListener('touchend', resetZoom);
        }
    });
</script>
@endpush
