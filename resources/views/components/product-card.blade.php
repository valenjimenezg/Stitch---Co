@props(['variante'])

<div class="bg-white rounded-2xl border border-slate-100 p-4 hover:shadow-2xl hover:shadow-primary/5 transition-all group relative {{ $variante->stock <= 0 ? 'grayscale opacity-[0.85]' : '' }}">

    {{-- Botón wishlist --}}
    @php $enWishlist = auth()->check() ? auth()->user()->listaDeseos()->where('variante_id', $variante->id)->exists() : false; @endphp
    <form method="POST" action="{{ route('wishlist.toggle') }}" class="absolute top-6 right-6 z-40" {!! $enWishlist ? 'onsubmit="confirmDeletion(event, \'¿Quitar de la lista?\', \'El producto será eliminado de tus deseos.\')"' : '' !!}>
        @csrf
        <input type="hidden" name="variante_id" value="{{ $variante->id }}">
        <button type="submit"
                class="size-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center transition-colors shadow-sm
                       {{ $enWishlist ? 'text-red-500' : 'text-slate-400 hover:text-red-500' }}">
            <span class="material-symbols-outlined text-xl">favorite</span>
        </button>
    </form>

    {{-- Overlay Agotado --}}
    @if($variante->stock <= 0)
    <div class="absolute inset-x-0 top-1/3 z-30 flex justify-center pointer-events-none">
        <div class="bg-slate-900 text-white font-black text-xl px-12 py-3 tracking-widest uppercase -rotate-12 shadow-2xl shadow-slate-900/50 outline outline-4 outline-offset-0 outline-white opacity-90">AGOTADO</div>
    </div>
    @endif

    {{-- Badge oferta (Ribbon 3D) --}}
    @if($variante->stock > 0 && $variante->en_oferta)
        <div class="absolute top-6 -left-2 z-40">
            <div class="bg-red-500 text-white text-[10px] font-black px-3 py-1.5 rounded-r uppercase shadow-md relative z-10">
                ¡OFERTA!
            </div>
            <div class="absolute top-full left-0 border-t-[6px] border-t-red-800 border-l-[8px] border-l-transparent z-0"></div>
        </div>
    @endif

    {{-- Imagen --}}
    <div class="aspect-[4/5] rounded-xl overflow-hidden mb-4 bg-slate-50 relative qv-hover-wrapper">
        @if($variante->imagen)
            <img class="w-full h-full object-cover text-slate-300"
                 src="{{ asset($variante->imagen) }}"
                 alt="{{ $variante->producto->nombre }}"
                 style="transition: transform 0.5s ease;"
                 onmouseover="this.style.transform='scale(1.05)'" 
                 onmouseout="this.style.transform='scale(1)'"/>
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
                <span class="material-symbols-outlined text-5xl">image</span>
            </div>
        @endif
        
        {{-- Botón Quick View --}}
        <button type="button" onclick="openQuickView({{ $variante->id }})" class="qv-hover-btn">
            VISTA RÁPIDA
        </button>
    </div>

    {{-- Info --}}
    <div>
        @if($variante->marca)
            <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">{{ $variante->marca }}</p>
        @endif
        <h5 class="font-bold text-slate-900 mb-1.5 leading-tight">
            <a href="{{ route('products.show', $variante->id) }}" class="hover:text-primary transition-colors">
                {{ $variante->producto->nombre }}
                @if($variante->color) <span class="text-slate-400 font-normal">- {{ $variante->color }}</span> @endif
            </a>
        </h5>

        @php
            $cat = strtolower(trim($variante->producto->categoria ?? ''));
            $unidad = '';
            if (in_array($cat, ['telas', 'tela'])) {
                $unidad = '';
            } elseif (in_array($cat, ['hilos', 'hilo', 'lanas', 'lana', 'estambres'])) {
                $unidad = $variante->grosor ? 'Grosor: ' . $variante->grosor : ($variante->cm ? $variante->cm . 'cm' : 'Unidad/Cono');
            } elseif (in_array($cat, ['kits', 'kit'])) {
                $unidad = 'Kit completo con accesorios';
            }
        @endphp
        
        @if($unidad)
            <p class="text-[11px] text-slate-500 font-medium mb-3 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">straighten</span> {{ $unidad }}
            </p>
        @else
            <div class="mb-3"></div>
        @endif

        <div class="flex items-center justify-between mt-auto">
            <div class="flex flex-col">
                <span class="text-xl font-black text-slate-900 leading-none">{{ bs($variante->precio_con_descuento) }}</span>
                <span class="text-[11px] font-bold text-slate-400 uppercase mt-1">Ref: ${{ number_format($variante->precio_con_descuento, 2) }}</span>
                @if($variante->en_oferta)
                    <span class="block text-[10px] text-slate-400 line-through mt-1">{{ bs($variante->precio) }}</span>
                @endif
            </div>
            @if($variante->stock > 0)
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit"
                        class="size-10 rounded-lg bg-primary text-white flex items-center justify-center hover:scale-105 active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                </button>
            </form>
            @else
            <a href="{{ route('products.show', $variante->id) }}" class="size-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Agotado - Avísame">
                <span class="material-symbols-outlined text-xl">notifications_active</span>
            </a>
            @endif
        </div>
    </div>
</div>
