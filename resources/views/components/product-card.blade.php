@props(['variante'])

@php
    $enWishlist = auth()->check() && is_array(auth()->user()->lista_deseos)
        ? in_array($variante->id, auth()->user()->lista_deseos)
        : false;

    $comentarios = $variante->producto->comentarios ?? collect([]);
    $promedio    = $comentarios->count() > 0 ? round($comentarios->avg('calificacion'), 1) : 0;

    // Obtener nombre de categoría (es una relación belongsTo, no una columna)
    $catNombre = $variante->producto->categoria->nombre ?? $variante->producto->categoria ?? '';
    $cat       = strtolower(trim(is_string($catNombre) ? $catNombre : ''));
    $agotado   = $variante->stock_disponible <= 0;

    // Resolver imagen: si la ruta no empieza por 'productos/' intentar prefijarla
    $imagenPath = $variante->imagen ?? null;
    if ($imagenPath && !str_starts_with($imagenPath, 'productos/') && !str_starts_with($imagenPath, 'http')) {
        $baseName = basename($imagenPath);
        if (file_exists(public_path('productos/' . $baseName))) {
            $imagenPath = 'productos/' . $baseName;
        }
    }

    $unidad = '';
    if (in_array($cat, ['hilos','hilo','lanas','lana','estambres'])) {
        $unidad = $variante->grosor ? 'Grosor: ' . $variante->grosor : ($variante->cm ? $variante->cm . ' cm' : 'Unidad / Cono');
    } elseif (in_array($cat, ['kits','kit'])) {
        $unidad = 'Kit completo';
    }

    // Variantes hermanas para los swatches
    $todasVariantes = $variante->producto->variantes ?? collect([]);
    $variantesConColor = $todasVariantes->filter(fn($v) => !empty($v->color));
    $mostrarSwatches = $todasVariantes->count() >= 1;

    // Mapa de colores nombrados → hex
    $colorMap = [
        'natural'     => '#e8dcc7', 'beige'       => '#f5f5dc', 'blanco'      => '#f8f8f8',
        'negro'       => '#1a1a1a', 'gris'        => '#94a3b8', 'gris plomo'  => '#475569',
        'gris perla'  => '#cbd5e1', 'plateado'    => '#c0c0c0', 'dorado'      => '#d4a017',
        'crema'       => '#fef3c7', 'rojo'        => '#ef4444', 'rosado'      => '#f472b6',
        'rosa'        => '#f9a8d4', 'fucsia'      => '#ec4899', 'naranja'     => '#f97316',
        'amarillo'    => '#eab308', 'verde'       => '#22c55e', 'verde oscuro'=> '#15803d',
        'azul'        => '#3b82f6', 'azul marino' => '#1e3a5f', 'celeste'     => '#7dd3fc',
        'morado'      => '#a855f7', 'lila'        => '#c084fc', 'turquesa'    => '#2dd4bf',
        'cafe'        => '#92400e', 'café'        => '#92400e', 'marrón'      => '#78350f',
        'único'       => '#94a3b8', 'unico'       => '#94a3b8',
    ];
@endphp

{{-- ══════════════════════════════════════════════ --}}
{{-- PRODUCT CARD                                   --}}
{{-- ══════════════════════════════════════════════ --}}
<article class="product-card {{ $agotado ? 'is-agotado' : '' }}">

    {{-- ── Imagen ────────────────────────────────── --}}
    <div class="pc-image-wrap">

        {{-- Wishlist --}}
        <form method="POST" action="{{ route('wishlist.toggle') }}" class="pc-wishlist"
              {!! $enWishlist ? 'onsubmit="confirmDeletion(event, \'¿Quitar de la lista?\', \'El producto será eliminado de tus deseos.\')"' : '' !!}>
            @csrf
            <input type="hidden" name="variante_id" value="{{ $variante->id }}">
            <button type="submit" class="pc-wishlist-btn {{ $enWishlist ? 'is-active' : '' }}" title="Lista de deseos">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' {{ $enWishlist ? 1 : 0 }}; font-size:18px;">favorite</span>
            </button>
        </form>

        {{-- Badge oferta --}}
        @if(!$agotado && $variante->en_oferta)
            <div class="pc-badge-oferta">
                <span class="material-symbols-outlined" style="font-size:12px; vertical-align:-2px;">local_offer</span>
                OFERTA
            </div>
        @endif

        {{-- Rating badge --}}
        @if($comentarios->count() > 0)
            <div class="pc-badge-rating" title="{{ $promedio }} de 5 estrellas">
                <span class="material-symbols-outlined" style="font-size:11px; color:#F5C518; font-variation-settings:'FILL' 1;">star</span>
                {{ $promedio }}
            </div>
        @endif

        {{-- Imagen / placeholder --}}
        @if($imagenPath)
            <img class="pc-img"
                 src="{{ asset($imagenPath) }}"
                 alt="{{ $variante->producto->nombre }}"
                 loading="lazy"/>
        @else
            <div class="pc-img-placeholder">
                <span class="material-symbols-outlined" style="font-size:48px; color:#cbd5e1;">image</span>
            </div>
        @endif

        {{-- Overlay Agotado --}}
        @if($agotado)
            <div class="pc-agotado-overlay">
                <span class="pc-agotado-label">AGOTADO</span>
            </div>
        @endif

        {{-- Quick View --}}
        <button type="button" onclick="openQuickView({{ $variante->id }})" class="pc-quickview">
            <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
            Vista rápida
        </button>

    </div>{{-- /pc-image-wrap --}}

    {{-- ── Info ──────────────────────────────────── --}}
    <div class="pc-body">

        <div class="pc-body-top">
            {{-- Marca / unidad --}}
            <div class="pc-meta">
                @if($variante->marca)
                    <span class="pc-marca">{{ $variante->marca }}</span>
                @endif
                @if($unidad)
                    <span class="pc-unidad">{{ $unidad }}</span>
                @endif
            </div>

            {{-- Nombre --}}
            <h5 class="pc-name">
                <a href="{{ route('products.show', $variante->id) }}">
                    {{ $variante->producto->nombre }}
                    @if($variante->color)<span class="pc-color">· {{ $variante->color }}</span>@endif
                </a>
            </h5>
        </div>

        {{-- Precios + carrito --}}
        <div class="pc-footer">
            <div class="pc-prices">
                <span class="pc-price-main">{{ bs($variante->precio_con_descuento) }}</span>
                <span class="pc-price-ref">REF ${{ number_format($variante->precio_con_descuento, 2) }}</span>
                @if($variante->en_oferta)
                    <span class="pc-price-old">{{ bs($variante->precio) }}</span>
                @endif
            </div>

            @if(!$agotado)
                <form method="POST" action="{{ route('cart.add') }}" class="pc-cart-form">
                    @csrf
                    <input type="hidden" name="variante_id" value="{{ $variante->id }}">
                    <input type="hidden" name="cantidad" value="1">
                    <button type="submit" class="pc-cart-btn" title="Añadir al carrito">
                        <span class="material-symbols-outlined" style="font-size:20px; font-variation-settings:'FILL' 1;">add_shopping_cart</span>
                    </button>
                </form>
            @else
                <a href="{{ route('products.show', $variante->id) }}" class="pc-notify-btn" title="Avísame cuando esté disponible">
                    <span class="material-symbols-outlined" style="font-size:18px;">notifications_active</span>
                </a>
            @endif
        </div>

    </div>{{-- /pc-body --}}

</article>

{{-- ══════════════════════════════════════════════ --}}
{{-- ESTILOS (una sola vez gracias a @once)          --}}
{{-- ══════════════════════════════════════════════ --}}
@once
@push('styles')
<style>
/* ── Card base ──────────────────────────────────── */
.product-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #f0ebff;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    height: 100%;                              /* llena el alto del grid cell */
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s;
    cursor: pointer;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 50px -12px rgba(109,40,217,0.18), 0 8px 20px -8px rgba(0,0,0,0.08);
    border-color: #ddd6fe;
}
.product-card.is-agotado { filter: grayscale(0.6); opacity: .8; }

/* ── Imagen ─────────────────────────────────────── */
.pc-image-wrap {
    position: relative;
    aspect-ratio: 4 / 5;                       /* proporción original vertical */
    overflow: hidden;
    background: #f8f7ff;
    flex-shrink: 0;                            /* no se comprime nunca */
}
.pc-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
    display: block;
}
.product-card:hover .pc-img { transform: scale(1.06); }
.pc-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}

/* ── Wishlist ────────────────────────────────────── */
.pc-wishlist {
    position: absolute; top: 12px; right: 12px; z-index: 30;
}
.pc-wishlist-btn {
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.6);
    display: flex; align-items: center; justify-content: center;
    color: #9ca3af;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    transition: color .2s, background .2s, transform .2s;
    cursor: pointer;
}
.pc-wishlist-btn:hover, .pc-wishlist-btn.is-active { color: #ef4444; background: #fff; }
.pc-wishlist-btn:hover { transform: scale(1.1); }

/* ── Badge oferta ───────────────────────────────── */
.pc-badge-oferta {
    position: absolute; top: 12px; left: 0; z-index: 30;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    font-size: 9px; font-weight: 900; letter-spacing: .08em; text-transform: uppercase;
    padding: 5px 10px 5px 10px;
    border-radius: 0 50px 50px 0;
    box-shadow: 0 2px 8px rgba(185,28,28,0.35);
}

/* ── Badge rating ───────────────────────────────── */
.pc-badge-rating {
    position: absolute; bottom: 10px; left: 10px; z-index: 20;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 11px; font-weight: 700;
    padding: 3px 8px;
    border-radius: 50px;
    display: flex; align-items: center; gap: 3px;
}

/* ── Agotado overlay ────────────────────────────── */
.pc-agotado-overlay {
    position: absolute; inset: 0; z-index: 25;
    display: flex; align-items: center; justify-content: center;
    background: rgba(15,23,42,0.28);
}
.pc-agotado-label {
    background: rgba(15,23,42,0.85);
    color: #fff;
    font-size: 13px; font-weight: 900; letter-spacing: .18em; text-transform: uppercase;
    padding: 8px 20px;
    border-radius: 6px;
    transform: rotate(-10deg);
    outline: 2px solid rgba(255,255,255,0.3);
    outline-offset: 2px;
}

/* ── Quick view ─────────────────────────────────── */
.pc-quickview {
    position: absolute; bottom: 0; left: 0; right: 0; z-index: 20;
    background: rgba(124,58,237,0.88);
    backdrop-filter: blur(6px);
    color: #fff;
    font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    padding: 9px;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    border: none; cursor: pointer;
    transform: translateY(100%);
    transition: transform .22s ease;
}
.product-card:hover .pc-quickview { transform: translateY(0); }

/* ── Body ───────────────────────────────────────── */
.pc-body {
    padding: 14px 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;                                   /* ocupa todo el espacio restante */
    justify-content: space-between;            /* empuja el footer siempre al fondo */
}
/* Grupo superior: meta + nombre */
.pc-body-top { display: flex; flex-direction: column; gap: 5px; }

/* ── Meta (marca + unidad) ──────────────────────── */
.pc-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; min-height: 16px; }
.pc-marca {
    font-size: 9px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
    color: #7c3aed;
    background: #f3f0ff;
    padding: 2px 7px; border-radius: 50px;
}
.pc-unidad { font-size: 10px; font-weight: 500; color: #9ca3af; }

/* ── Swatches de color ──────────────────────────── */
.pc-swatches {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 6px;
}
.pc-swatch {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: block;
    position: relative;
    overflow: hidden;
    border: 1.5px solid rgba(0,0,0,0.10);
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    transition: transform .18s ease, box-shadow .18s ease;
    flex-shrink: 0;
}
.pc-swatch:hover {
    transform: scale(1.18);
    box-shadow: 0 3px 8px rgba(0,0,0,0.20);
}
.pc-swatch.is-active {
    border: 2px solid #7c3aed;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #7c3aed;
    transform: scale(1.12);
}
.pc-swatch.is-agotado-sw {
    opacity: 0.7;
}
/* Raya diagonal para agotados */
.pc-swatch-slash {
    position: absolute;
    inset: 0;
    display: block;
}
.pc-swatch-slash::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -10%;
    width: 120%;
    height: 1.5px;
    background: rgba(30,27,75,0.7);
    transform: rotate(-45deg);
    transform-origin: center;
}
.pc-swatch-more {
    font-size: 10px;
    font-weight: 700;
    color: #9ca3af;
    line-height: 22px;
    padding-left: 2px;
}

/* ── Nombre ─────────────────────────────────────── */
.pc-name {
    font-size: 13.5px; font-weight: 700; color: #1e1b4b;
    line-height: 1.35; margin: 0;
}
.pc-name a { color: inherit; text-decoration: none; transition: color .15s; }
.pc-name a:hover { color: #7c3aed; }
.pc-color { font-weight: 400; color: #9ca3af; font-size: 12px; }

/* ── Footer: precios + carrito ──────────────────── */
.pc-footer {
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 8px;
    margin-top: 12px;                          /* separación del contenido de arriba */
    padding-top: 10px;                         /* línea divisoria */
    border-top: 1px solid #f3f0ff;             /* divisor lila muy sutil */
}
.pc-prices { display: flex; flex-direction: column; gap: 1px; }
.pc-price-main {
    font-size: 17px; font-weight: 900; color: #1e1b4b; line-height: 1;
    letter-spacing: -.3px;
}
.pc-price-ref {
    font-size: 10px; font-weight: 600; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .05em;
}
.pc-price-old {
    font-size: 10px; color: #9ca3af; text-decoration: line-through;
}

/* ── Botón carrito ──────────────────────────────── */
.pc-cart-form { flex-shrink: 0; }
.pc-cart-btn {
    width: 38px; height: 38px; border-radius: 12px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff;
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(109,40,217,0.35);
    transition: transform .18s, box-shadow .18s;
}
.pc-cart-btn:hover { transform: scale(1.08); box-shadow: 0 6px 18px rgba(109,40,217,0.45); }
.pc-cart-btn:active { transform: scale(0.94); }

/* ── Notificar (agotado) ────────────────────────── */
.pc-notify-btn {
    width: 38px; height: 38px; border-radius: 12px;
    background: #f3f4f6; color: #9ca3af;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s, color .2s;
    flex-shrink: 0;
}
.pc-notify-btn:hover { background: #f3f0ff; color: #7c3aed; }
</style>
@endpush
@endonce
