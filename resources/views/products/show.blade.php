@extends('layouts.app')

@section('title', ($variante->producto->nombre ?? 'Producto') . ' — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="flex items-center gap-2 text-sm text-slate-400 mb-10">
    <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <a class="hover:text-primary transition-colors" href="{{ route('categories.show', $variante->producto->categoria->nombre ?? 'all') }}">{{ $variante->producto->categoria->nombre ?? 'Categoría' }}</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-slate-900 font-semibold">{{ $variante->producto->nombre ?? '—' }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

    {{-- Image Gallery Custom Built --}}
    <style>
        .custom-gallery-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .main-image-wrapper {
            width: 100%;
            height: clamp(350px, 60vh, 600px);
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            cursor: zoom-in;
            display: flex;
            align-items: center;
            justify-center: center;
        }
        .main-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.1s ease-out;
            transform-origin: center center;
        }
        .main-image-wrapper video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
        }
        .thumbnails-wrapper {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
        }
        .thumbnail-item {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 2px solid transparent;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.2s;
            background-color: #f8fafc;
            position: relative;
        }
        .thumbnail-item.active {
            border-color: #0f172a;
        }
        .thumbnail-item img, .thumbnail-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .thumbnail-item.video-thumb::after {
            content: "▶";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
    </style>

    <div class="custom-gallery-container w-full">
        {{-- Visualizador Principal --}}
        <div class="main-image-wrapper" id="custom-main-wrapper">
            @php $imagenPrincipal = $variante->imagen ?? $variante->producto->imagen; @endphp
            
            <img id="custom-main-image" 
                 src="{{ $imagenPrincipal ? asset($imagenPrincipal) : '' }}" 
                 alt="{{ $variante->producto->nombre ?? '' }}" 
                 style="display: {{ $imagenPrincipal ? 'block' : 'none' }};" />
                 
            <video id="custom-main-video" controls style="display: none;"></video>

            @if(!$imagenPrincipal && empty($variante->producto->galeria))
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #cbd5e1;">
                <span class="material-symbols-outlined text-6xl">straighten</span>
            </div>
            @endif
        </div>

        {{-- Miniaturas (Abajo) --}}
        <div class="thumbnails-wrapper">
            @if($imagenPrincipal)
                <div class="thumbnail-item active" onclick="updateMainMedia('{{ asset($imagenPrincipal) }}', 'image', this)">
                    <img src="{{ asset($imagenPrincipal) }}" />
                </div>
            @endif

            @php $galeria = $variante->producto->galeria ?? []; @endphp
            @if(is_array($galeria))
                @foreach($galeria as $media)
                    @php 
                        $is_video = \Illuminate\Support\Str::endsWith(strtolower($media), ['.mp4', '.mov', '.webm', '.ogg']);
                        $media_url = asset($media);
                    @endphp
                    <div class="thumbnail-item {{ $is_video ? 'video-thumb' : '' }}" onclick="updateMainMedia('{{ $media_url }}', '{{ $is_video ? 'video' : 'image' }}', this)">
                        @if($is_video)
                            <video src="{{ $media_url }}#t=0.1" muted preload="metadata"></video>
                        @else
                            <img src="{{ $media_url }}" />
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Product Info --}}
    <div class="flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1.5 rounded-full tracking-wider uppercase">
                {{ $variante->producto->categoria->nombre ?? 'Producto' }}
            </span>
            
            @php
                $cat = strtolower(trim($variante->producto->categoria->nombre ?? ''));
                $unidad = '';
                if (!empty($variante->unidad_medida)) {
                    $unidad = 'Se vende por: ' . $variante->unidad_medida;
                } else {
                    if (in_array($cat, ['telas', 'tela'])) {
                        $unidad = '';
                    } elseif (in_array($cat, ['hilos', 'hilo', 'lanas', 'lana', 'estambres'])) {
                        $unidad = $variante->grosor ? 'Grosor: ' . $variante->grosor : ($variante->cm ? $variante->cm . 'cm' : 'Se vende por unidad');
                    } elseif (in_array($cat, ['kits', 'kit'])) {
                        $unidad = 'Kit Set Completo';
                    }
                }
            @endphp
            
            @if($unidad)
                <span class="text-slate-400 text-xs font-bold px-3 py-1.5 rounded-full border border-slate-200 flex items-center gap-1.5 bg-slate-50">
                    <span class="material-symbols-outlined text-[14px]">info</span> {{ $unidad }}
                </span>
            @endif
        </div>

        <h2 class="text-4xl font-black text-slate-900 mb-3 leading-tight">{{ $variante->producto->nombre ?? '—' }}</h2>

        <div class="flex items-start gap-4 mb-8">
            <div class="flex flex-col">
                <div class="flex items-baseline gap-4">
                    @if($variante->en_oferta && $variante->descuento_porcentaje > 0)
                        <span id="product-price-display" class="text-4xl font-black text-primary">{{ bs($variante->precio_con_descuento) }}{{ $variante->unidad_medida && strtolower($variante->unidad_medida) !== 'ninguna' ? ' / ' . strtolower($variante->unidad_medida) : '' }}</span>
                        <span id="product-old-price-display" class="text-xl text-slate-400 line-through">{{ bs($variante->precio) }}</span>
                        <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full relative -top-1">-{{ $variante->descuento_porcentaje }}%</span>
                    @else
                        <span id="product-price-display" class="text-4xl font-black text-primary">{{ bs($variante->precio) }}{{ $variante->unidad_medida && strtolower($variante->unidad_medida) !== 'ninguna' ? ' / ' . strtolower($variante->unidad_medida) : '' }}</span>
                    @endif
                </div>
                <span class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1 block">Ref: ${{ number_format($variante->en_oferta && $variante->descuento_porcentaje > 0 ? $variante->precio_con_descuento : $variante->precio, 2) }}</span>
            </div>

            @if($variante->stock_disponible > 0)
                <span class="text-emerald-500 flex items-center gap-1 text-sm font-bold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                    <span class="material-symbols-outlined text-lg">check_circle</span> Disponibles: {{ $variante->stock_disponible }} unidades
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

        {{-- Color disponible --}}
        <div class="mb-8">
            <span class="block text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest">
                Color disponible: <span class="text-slate-500 font-normal">{{ $variante->color ?? '—' }}</span>
            </span>
            <div class="flex gap-4 flex-wrap">
                @foreach($variante->producto->variantes as $v)
                    @php 
                        $isOutOfStock = $v->stock_disponible <= 0; 
                        $isActive = $v->id === $variante->id;
                    @endphp
                    
                    @if($v->color)
                        @php
                            $colorName = strtolower(trim($v->color));
                            $colorMap = [
                                'natural' => '#e8dcc7',
                                'blanco' => '#ffffff',
                                'beige' => '#f5f5dc',
                                'negro' => '#1a1a1a',
                                'rojo' => '#ef4444',
                                'azul' => '#3b82f6',
                                'verde' => '#22c55e',
                                'amarillo' => '#eab308',
                                'gris plomo' => '#475569',
                                'gris perla' => '#cbd5e1',
                                'plateado' => '#94a3b8',
                                'dorado' => '#ca8a04',
                                'crema' => '#fef3c7',
                                'rosado' => '#f472b6',
                                'rosa' => '#f472b6',
                                'morado' => '#a855f7',
                                'naranja' => '#f97316',
                                'gris' => '#94a3b8',
                                'único' => '#94a3b8',
                                'unico' => '#94a3b8',
                                'fucsia' => '#ec4899',
                                'celeste' => '#7dd3fc',
                                'turquesa' => '#2dd4bf',
                                'lila' => '#c084fc',
                                'café' => '#92400e',
                                'cafe' => '#92400e',
                                'marrón' => '#78350f',
                                'mostaza' => '#ca8a04',
                                'verde oscuro' => '#15803d',
                                'azul marino' => '#1e3a5f',
                            ];
                            $hex = $colorMap[$colorName] ?? $colorName;
                            
                        @endphp
                        
                        <a href="{{ route('products.show', $v->id) }}"
                           title="{{ $v->grosor ? $v->grosor . ' | ' : '' }}{{ $v->color }}{{ $isOutOfStock ? ' (Agotado)' : '' }}"
                           class="relative block rounded-full transition-all duration-200 cursor-pointer
                                  {{ $isActive ? 'ring-2 ring-black ring-offset-2 scale-110' : 'hover:scale-110 hover:ring-1 hover:ring-slate-300 hover:ring-offset-1' }}
                                  {{ $isOutOfStock ? 'opacity-50' : '' }}"
                           style="width: 36px; height: 36px; background-color: {{ $hex }}; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05);">
                            
                            @if($isOutOfStock)
                                <span class="absolute block w-[110%] h-[1.5px] bg-slate-600 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -rotate-45 shadow-sm"></span>
                            @endif
                        </a>

                    @else
                        {{-- Fallback para variantes sin color (ej: Tallas puras) --}}
                        <a href="{{ route('products.show', $v->id) }}"
                           class="px-4 py-2 rounded-xl border-2 text-sm font-bold transition-all relative overflow-hidden flex items-center gap-2
                                  {{ $isActive ? 'border-primary bg-primary/5 text-primary shadow-inner' : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white shadow-sm' }}
                                  {{ $isOutOfStock ? 'opacity-60 bg-slate-50' : '' }}">
                            <span>{{ $v->grosor ? $v->grosor . ' | ' : '' }}{{ $v->talla ?? 'Variante ' . $loop->iteration }}</span>
                            @if($isOutOfStock)
                                <span class="absolute block w-full h-[1.5px] bg-slate-400 top-1/2 left-0 -translate-y-1/2 -rotate-12"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        </div>


        {{-- Add to Cart --}}
        @if($variante->stock_disponible > 0)
        <form action="{{ route('cart.add') }}" method="POST" class="mb-10">
            @csrf
            <input type="hidden" name="variante_id" value="{{ $variante->id }}">

            @if($variante->empaques && $variante->empaques->count() > 0)
            <div class="mb-5">
                <span class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-widest">
                    Presentación
                </span>
                <select name="empaque_id" id="empaque_select" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3 px-4 font-semibold text-slate-800" onchange="updatePresentation()">
                    @foreach($variante->empaques as $empaque)
                        @php
                            $p_usd = $empaque->precio;
                            $p_usd_desc = $p_usd;
                            if($variante->en_oferta && $variante->descuento_porcentaje > 0) {
                                $p_usd_desc = $p_usd * (1 - $variante->descuento_porcentaje / 100);
                            }
                        @endphp
                        <option value="{{ $empaque->id }}" 
                                data-factor="{{ $empaque->factor_conversion }}"
                                data-name="{{ $empaque->unidad_medida }}"
                                data-preciobs="{{ bs($p_usd) }}"
                                data-preciodesc="{{ bs($p_usd_desc) }}">
                            {{ $empaque->unidad_medida }} {{ $empaque->factor_conversion > 1 ? '('.$empaque->factor_conversion.' uds)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-6">
                @php
                    $u = strtolower($variante->unidad_medida ?? '');
                    $isFractional = in_array($u, ['metro', 'centímetro', 'cm']);
                    $step = $isFractional ? '0.5' : '1';
                    $min = $isFractional ? '0.5' : '1';
                @endphp
                <span class="block text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest" id="cantidad_label">
                    Cantidad {!! !empty($u) && $u !== 'ninguna' ? '<span id="lbl_unidad">(' . $u . 's)</span>' : '' !!}
                </span>
                <div class="flex items-center gap-6">
                    <div class="flex items-center bg-white rounded-xl p-1.5 border border-slate-200 shadow-sm">
                        <button type="button" onclick="changeQty(-{{ $step }})" class="size-10 flex items-center justify-center hover:bg-slate-50 rounded-lg transition-colors text-slate-600">
                            <span class="material-symbols-outlined">remove</span>
                        </button>
                        <input type="number" name="cantidad" id="cantidad" value="{{ $min }}" min="{{ $min }}" max="{{ $variante->stock_disponible }}" step="{{ $step }}"
                               class="w-16 text-center font-bold text-lg border-none focus:ring-0 bg-transparent">
                        <button type="button" onclick="changeQty({{ $step }})" class="size-10 flex items-center justify-center hover:bg-slate-50 rounded-lg transition-colors text-slate-600">
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                    <span class="text-slate-400 text-sm italic font-medium" id="stock-display">Quedan {{ $variante->stock_disponible }} {{ $isFractional ? $u.'s' : 'unidades' }}</span>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-bold py-5 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-lg shadow-primary/30">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Agregar al carrito
                </button>

                @php $enWishlist = auth()->check() && is_array(auth()->user()->lista_deseos) ? in_array($variante->id, auth()->user()->lista_deseos) : false; @endphp
                
                <button type="button" id="wishlist-btn" 
                        data-url="{{ route('wishlist.toggle') }}" 
                        data-id="{{ $variante->id }}"
                        class="px-6 border-2 rounded-2xl transition-all h-full {{ $enWishlist ? 'border-red-200 bg-red-50 text-red-500 hover:bg-red-100' : 'border-slate-100 hover:border-primary/20 hover:bg-primary/5 text-slate-400 hover:text-primary' }}" 
                        title="{{ $enWishlist ? 'Quitar de lista de deseos' : 'Añadir a lista de deseos' }}">
                    <span id="wishlist-icon" class="material-symbols-outlined text-3xl" {!! $enWishlist ? 'style="font-variation-settings:\'FILL\' 1"' : '' !!}>favorite</span>
                </button>
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

        {{-- Ficha Técnica + Instrucciones de Uso: Layout en 2 columnas si hay instrucciones --}}
        <div class="mt-10 pt-8 border-t border-slate-100">
            @php
                $catLower = strtolower(trim($variante->producto->categoria->nombre ?? ''));
                $esTela = str_contains($catLower, 'tela') || str_contains($catLower, 'retazo') || str_contains($catLower, 'cinta') || str_contains($catLower, 'encaje');
                $esBoton = str_contains($catLower, 'boton') || str_contains($catLower, 'botón') || str_contains($catLower, 'cierre') || str_contains($catLower, 'broche');
                $esLana = str_contains($catLower, 'hilo') || str_contains($catLower, 'lana') || str_contains($catLower, 'estambre');
                $esElastico = str_contains($catLower, 'elastico') || str_contains($catLower, 'elástico') || str_contains($catLower, 'vivo');
                $esHerramienta = str_contains($catLower, 'herramienta') || str_contains($catLower, 'accesorio') || str_contains($catLower, 'aguja');
                $tieneInstrucciones = !empty($variante->producto->instrucciones_uso);
            @endphp

            <div class="{{ $tieneInstrucciones ? 'grid grid-cols-1 md:grid-cols-2 gap-8 items-start' : '' }}">

                {{-- COLUMNA IZQUIERDA: Instrucciones de Uso --}}
                @if($tieneInstrucciones)
                <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl p-6 border border-primary/15">
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary bg-white p-1.5 rounded-lg shadow-sm">menu_book</span>
                        Instrucciones de Uso
                    </h3>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line text-base">{{ $variante->producto->instrucciones_uso }}</p>
                </div>
                @endif

                {{-- COLUMNA DERECHA: Ficha Técnica --}}
                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">description</span>
                        Ficha Técnica
                    </h3>
                    <div class="grid grid-cols-2 gap-4">

                        {{-- 1. GROSOR / ESPESOR --}}
                        @if($variante->grosor)
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-2 text-primary mb-2">
                                <span class="material-symbols-outlined bg-primary/10 p-1.5 rounded-lg text-sm">straighten</span>
                                <span class="font-black uppercase text-xs tracking-widest">
                                    {{ $esBoton || $esHerramienta ? 'Grosor / Espesor' : ($esTela ? 'Peso / Textura' : 'Grosor') }}
                                </span>
                            </div>
                            <p class="text-base font-semibold text-slate-900">{{ $variante->grosor }}</p>
                        </div>
                        @endif

                        {{-- 2. COLOR / DISEÑO --}}
                        @if($variante->color)
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-2 text-primary mb-2">
                                <span class="material-symbols-outlined bg-primary/10 p-1.5 rounded-lg text-sm">palette</span>
                                <span class="font-black uppercase text-xs tracking-widest">
                                    {{ $esHerramienta ? 'Color / Acabado' : 'Color / Tono' }}
                                </span>
                            </div>
                            <p class="text-base font-semibold text-slate-900">{{ $variante->color }}</p>
                        </div>
                        @endif

                        {{-- 3. MEDIDO / CM --}}
                        @if($variante->cm)
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-2 text-primary mb-2">
                                <span class="material-symbols-outlined bg-primary/10 p-1.5 rounded-lg text-sm">architecture</span>
                                <span class="font-black uppercase text-xs tracking-widest">
                                    @if($esBoton) Diámetro / Tamaño
                                    @elseif($esTela || $esLana || $esElastico) Rendimiento / Largo
                                    @elseif($esHerramienta) Dimensiones
                                    @else Medida
                                    @endif
                                </span>
                            </div>
                            @php
                                $medida = floatval($variante->cm);
                                $textoMedida = $medida . ' cm';
                                if (($esTela || $esLana || $esElastico) && $medida >= 100) {
                                    $metros = $medida / 100;
                                    $textoMedida = $metros . ' ' . ($metros == 1 ? 'Metro' : 'Metros');
                                } elseif ($esBoton && $medida < 2) {
                                     $textoMedida = ($medida * 10) . ' mm';
                                }
                            @endphp
                            <p class="text-base font-semibold text-slate-900">{{ $textoMedida }}</p>
                        </div>
                        @endif

                        {{-- 4. MARCA --}}
                        @if($variante->marca)
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-2 text-primary mb-2">
                                <span class="material-symbols-outlined bg-primary/10 p-1.5 rounded-lg text-sm">sell</span>
                                <span class="font-black uppercase text-xs tracking-widest">Marca / Etiqueta</span>
                            </div>
                            <p class="text-base font-semibold text-slate-900">{{ $variante->marca }}</p>
                        </div>
                        @else
                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-2 text-primary mb-2">
                                <span class="material-symbols-outlined bg-primary/10 p-1.5 rounded-lg text-sm">category</span>
                                <span class="font-black uppercase text-xs tracking-widest">Familia</span>
                            </div>
                            <p class="text-base font-semibold text-slate-900">{{ $variante->producto->categoria->nombre ?? '—' }}</p>
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
        
    </div>
</div>

{{-- Reviews Section --}}
<div class="mt-20 pt-16 border-t border-slate-200">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        
        <!-- IZQUIERDA: Reseñas Existentes y Estadísticas -->
        <div class="lg:col-span-2 order-2 lg:order-1">
            <div class="mb-10">
                <h3 class="text-3xl font-black text-slate-900 flex items-center gap-3">
                    <span class="material-symbols-outlined text-4xl text-amber-400" style="font-variation-settings: 'FILL' 1">star</span>
                    Reseñas de Clientes
                </h3>
                <p class="text-slate-500 mt-2">Opiniones reales de usuarios que compraron este producto.</p>
            </div>
            
            @php
                $reviews = $variante->producto->comentarios()->where('aprobado', true)->latest()->get();
                $avgResenas = $reviews->avg('calificacion') ?? 0;
                $totalResenas = $reviews->count();
            @endphp

            {{-- Resumen de calificación --}}
            @if($totalResenas > 0)
            <div class="flex items-center gap-6 mb-8 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-center">
                    <p class="text-5xl font-black text-slate-900">{{ number_format($avgResenas, 1) }}</p>
                    <div class="flex justify-center gap-0.5 mt-1 text-amber-400">
                        @for($i=1; $i<=5; $i++)
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' {{ $i <= round($avgResenas) ? '1' : '0' }}">star</span>
                        @endfor
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $totalResenas }} {{ $totalResenas === 1 ? 'reseña' : 'reseñas' }}</p>
                </div>
                <div class="flex-1">
                    @for($star=5; $star>=1; $star--)
                        @php $count = $reviews->where('calificacion', $star)->count(); $pct = $totalResenas > 0 ? ($count / $totalResenas) * 100 : 0; @endphp
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-slate-500 w-4">{{ $star }}</span>
                            <span class="material-symbols-outlined text-xs text-amber-400" style="font-variation-settings: 'FILL' 1">star</span>
                            <div class="flex-1 bg-slate-100 rounded-full h-2">
                                <div class="bg-amber-400 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 w-5 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>
            @endif

            <div class="space-y-6">
                @if($totalResenas > 0)
                    @foreach($reviews as $review)
                        <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            {{-- Cabecera: avatar + nombre + fecha + badges --}}
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg uppercase flex-shrink-0">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</h4>
                                            @if($review->verified_purchase)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                                    <span class="material-symbols-outlined text-[11px]">verified</span> Compra verificada
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5 text-amber-400 flex-shrink-0">
                                    @for($i=1; $i<=5; $i++)
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' {{ $i <= $review->calificacion ? '1' : '0' }}">star</span>
                                    @endfor
                                </div>
                            </div>

                            {{-- Título de la reseña --}}
                            @if($review->titulo)
                                <p class="font-black text-slate-900 mb-1">{{ $review->titulo }}</p>
                            @endif

                            {{-- Comentario --}}
                            <p class="text-slate-600 leading-relaxed">{{ $review->comentario }}</p>

                            {{-- Respuesta del Admin --}}
                            @if($review->respuesta_admin)
                                <div class="mt-4 pl-4 border-l-2 border-primary/30 bg-primary/5 rounded-r-xl py-3 pr-3">
                                    <p class="text-xs font-black text-primary uppercase tracking-wider mb-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">storefront</span> Respuesta de Stitch & Co
                                    </p>
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $review->respuesta_admin }}</p>
                                    @if($review->respondido_at)
                                        <span class="text-xs text-slate-400 mt-1 block">{{ $review->respondido_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-100 border-dashed">
                        <span class="material-symbols-outlined text-5xl text-slate-300 mb-3 block">forum</span>
                        <h4 class="font-bold text-slate-900 mb-1">Aún no hay reseñas</h4>
                        <p class="text-sm text-slate-500">¡Sé el primero en compartir tu experiencia!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- DERECHA: Formulario de Nueva Reseña -->
        <div class="lg:col-span-1 order-1 lg:order-2">
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 sticky top-28">
                <h4 class="font-bold text-slate-900 mb-4">Escribe una reseña</h4>
                @auth
                    <form action="{{ route('products.reviews.store', $variante->producto->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Calificación</label>
                            <div class="flex items-center gap-1 rating-stars">
                                @for($i=1; $i<=5; $i++)
                                    <input type="radio" id="star{{$i}}" name="calificacion" value="{{$i}}" class="hidden" required>
                                    <label for="star{{$i}}" 
                                           class="material-symbols-outlined text-3xl text-slate-300 cursor-pointer transition-colors star-label" 
                                           data-value="{{$i}}"
                                           style="font-variation-settings: 'FILL' 1">star</label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Título (opcional)</label>
                            <input type="text" name="titulo" maxlength="120"
                                   class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3 px-4"
                                   placeholder='Ej: "¡Excelente calidad!"'>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const labels = document.querySelectorAll('.star-label');
                                    const inputs = document.querySelectorAll('input[name="calificacion"]');
                                    
                                    function updateStars(value) {
                                        labels.forEach(label => {
                                            if (parseInt(label.dataset.value) <= value) {
                                                label.classList.add('text-amber-400');
                                                label.classList.remove('text-slate-300');
                                            } else {
                                                label.classList.remove('text-amber-400');
                                                label.classList.add('text-slate-300');
                                            }
                                        });
                                    }

                                    labels.forEach(label => {
                                        label.addEventListener('mouseover', function() {
                                            updateStars(this.dataset.value);
                                        });
                                        label.addEventListener('mouseout', function() {
                                            const checked = document.querySelector('input[name="calificacion"]:checked');
                                            updateStars(checked ? checked.value : 0);
                                        });
                                    });

                                    inputs.forEach(input => {
                                        input.addEventListener('change', function() {
                                            updateStars(this.value);
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tu Comentario</label>
                            <textarea name="comentario" rows="3" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3 px-4 resize-none" placeholder="¿Qué te pareció el producto?" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition-all shadow-md">
                            Publicar Reseña
                        </button>
                    </form>
                @else
                    <p class="text-slate-600 text-sm mb-4">Para compartir tu experiencia con este producto, necesitas iniciar sesión.</p>
                    <a href="{{ route('login') }}" class="block text-center w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition-all shadow-md">
                        Iniciar Sesión
                    </a>
                @endauth
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
        const max = parseFloat(input.max);
        const min = parseFloat(input.min) || 1;
        let newVal = parseFloat(input.value) + parseFloat(delta);
        
        // Fix JS floating precision
        newVal = Math.round(newVal * 100) / 100;
        
        if (newVal >= min && newVal <= max) {
            input.value = newVal;
        }
    }

    // --- VISUALIZADOR DE IMAGEN Y ZOOM ROBUSTO FRONTEND ---
    const mainWrapper = document.getElementById('custom-main-wrapper');
    const mainImage = document.getElementById('custom-main-image');
    const mainVideo = document.getElementById('custom-main-video');
    let isVideoPlaying = false;

    // Efecto Zoom estilo lupa (Vanilla JS)
    function setupZoom() {
        if (!mainWrapper || !mainImage) return;

        mainWrapper.addEventListener('mousemove', function(e) {
            if (isVideoPlaying || mainImage.style.display === 'none') return;
            
            const rect = mainWrapper.getBoundingClientRect();
            
            // Posición exacta del mouse relativa al contenedor
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Calculamos porcentajes (0 a 100%)
            const xPercent = (x / rect.width) * 100;
            const yPercent = (y / rect.height) * 100;

            mainImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
            mainImage.style.transform = 'scale(2.5)'; // Nivel de zoom
        });

        mainWrapper.addEventListener('mouseleave', function() {
            mainImage.style.transformOrigin = 'center center';
            mainImage.style.transform = 'scale(1)';
        });
        
        mainWrapper.addEventListener('touchmove', function(e) {
            if (isVideoPlaying || mainImage.style.display === 'none') return;
            if (e.touches.length > 0) {
                e.preventDefault(); // Evitamos scroll web si el usuario hace swipe sobre la foto
                
                const rect = mainWrapper.getBoundingClientRect();
                const x = e.touches[0].clientX - rect.left;
                const y = e.touches[0].clientY - rect.top;
                
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;

                mainImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
                mainImage.style.transform = 'scale(2.5)';
            }
        }, { passive: false });
        
        mainWrapper.addEventListener('touchend', function() {
            mainImage.style.transformOrigin = 'center center';
            mainImage.style.transform = 'scale(1)';
        });
    }

    // Cambiar Imagen Principal por Miniatura
    window.updateMainMedia = function(url, type, element) {
        // Pausar si había video
        if (mainVideo) mainVideo.pause();

        if (type === 'video') {
            mainImage.style.display = 'none';
            mainVideo.src = url;
            mainVideo.style.display = 'block';
            mainVideo.play();
            isVideoPlaying = true;
            mainWrapper.style.cursor = 'default';
            
            // Limpiar zoom de la foto
            mainImage.style.transform = 'scale(1)';
        } else {
            mainVideo.style.display = 'none';
            mainImage.src = url;
            mainImage.style.display = 'block';
            isVideoPlaying = false;
            mainWrapper.style.cursor = 'zoom-in';
        }

        // Remarcar miniatura activa
        document.querySelectorAll('.thumbnail-item').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        }
    };

    // Auto-Inicializar zoom
    setupZoom();

    // Toggle de Wishlist Async
    const wishlistBtn = document.getElementById('wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            @auth
            const url = wishlistBtn.getAttribute('data-url');
            const varianteId = wishlistBtn.getAttribute('data-id');
            const icon = document.getElementById('wishlist-icon');
            const isRed = wishlistBtn.classList.contains('text-red-500');

            function toggleWishlistRequest() {
                wishlistBtn.classList.add('opacity-50', 'pointer-events-none');
                
                // Construct form data to send
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('variante_id', varianteId);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    wishlistBtn.classList.remove('opacity-50', 'pointer-events-none');
                    if(data.success) {
                        if(data.in_wishlist) {
                            // Se agregó a deseos (Corazón Rojo)
                            wishlistBtn.className = "px-6 border-2 rounded-2xl transition-all h-full border-red-200 bg-red-50 text-red-500 hover:bg-red-100";
                            icon.style.fontVariationSettings = "'FILL' 1";
                            wishlistBtn.title = "Quitar de lista de deseos";
                            
                            Swal.fire({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                icon: 'success',
                                title: data.message
                            });
                        } else {
                            // Se quitó de deseos (Corazón Blanco)
                            wishlistBtn.className = "px-6 border-2 rounded-2xl transition-all h-full border-slate-100 hover:border-primary/20 hover:bg-primary/5 text-slate-400 hover:text-primary";
                            icon.style.fontVariationSettings = "normal";
                            wishlistBtn.title = "Añadir a lista de deseos";
                            
                            Swal.fire({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                icon: 'info',
                                title: data.message
                            });
                        }
                    }
                }).catch(err => {
                    wishlistBtn.classList.remove('opacity-50', 'pointer-events-none');
                    console.error('Error toggling wishlist:', err);
                });
            }

            if (isRed) {
                // Confirmación para QUITAR de la lista de deseos
                Swal.fire({
                    title: '¿Deseas quitar este producto?',
                    text: 'Se eliminará de tu lista de deseos, pero podrás volver a agregarlo en cualquier momento.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Sí, quitar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        title: 'text-xl font-bold text-slate-800',
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg font-bold px-6 outline-none',
                        cancelButton: 'rounded-lg font-bold px-6 outline-none'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        toggleWishlistRequest();
                    }
                });
            } else {
                // Lo añade de una vez sin preguntar
                toggleWishlistRequest();
            }
            @else
            // Si el usuario es invitado, redirige al login
            window.location.href = "{{ route('login') }}?_tab=registro";
            @endauth
        });
    }

    const masterStock = {{ $variante->stock_disponible }};

    window.updatePresentation = function() {
        const select = document.getElementById('empaque_select');
        if (!select) return;

        const option = select.options[select.selectedIndex];
        const factor = parseInt(option.getAttribute('data-factor')) || 1;
        const name = option.getAttribute('data-name') || 'Unidad';
        const priceBs = option.getAttribute('data-preciobs');
        const priceDesc = option.getAttribute('data-preciodesc');
        
        // Update price display
        const priceDisplay = document.getElementById('product-price-display');
        const oldPriceDisplay = document.getElementById('product-old-price-display');
        
        if (oldPriceDisplay) {
            priceDisplay.innerHTML = priceDesc + ' <span class="text-2xl text-slate-400 font-normal">/ ' + name.toLowerCase() + '</span>';
            oldPriceDisplay.innerHTML = priceBs;
        } else if (priceDisplay) {
            priceDisplay.innerHTML = priceBs + ' <span class="text-2xl text-slate-400 font-normal">/ ' + name.toLowerCase() + '</span>';
        }

        // Update Stock Logic
        const availableInPack = Math.floor(masterStock / factor);
        
        const qtyInput = document.getElementById('cantidad');
        const stockDisplay = document.getElementById('stock-display');
        const lblUnidad = document.getElementById('lbl_unidad');
        const btnAdd = document.querySelector('button[type="submit"]');

        if (lblUnidad) lblUnidad.innerText = '(' + name.toLowerCase() + 's)';
        stockDisplay.innerText = "Quedan " + availableInPack + " " + name.toLowerCase() + (availableInPack !== 1 ? 's' : '');
        
        qtyInput.max = availableInPack;
        if(parseFloat(qtyInput.value) > availableInPack) {
            qtyInput.value = availableInPack;
        }

        if (availableInPack <= 0) {
            btnAdd.disabled = true;
            btnAdd.classList.add('opacity-50', 'cursor-not-allowed');
            btnAdd.innerHTML = '<span class="material-symbols-outlined">block</span> Insuficiente stock para esta presentación';
        } else {
            btnAdd.disabled = false;
            btnAdd.classList.remove('opacity-50', 'cursor-not-allowed');
            btnAdd.innerHTML = '<span class="material-symbols-outlined">shopping_cart</span> Agregar al carrito';
            if (parseFloat(qtyInput.value) < 1) qtyInput.value = 1;
        }
    }

    window.changeQty = function(amount) {
        const input = document.getElementById('cantidad');
        let val = parseFloat(input.value) + amount;
        let max = parseFloat(input.max) || {{ $variante->stock_disponible }};
        let min = parseFloat(input.min) || 1;
        if(val >= min && val <= max) {
            input.value = val;
        } else if (val > max) {
            input.value = max;
        } else if (val < min) {
            input.value = min;
        }
    }
    
    // Initialize if Empaques are present
    document.addEventListener('DOMContentLoaded', () => {
        updatePresentation();
    });
</script>
@endpush
