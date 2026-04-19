@props([
    'iconOnly' => false,
])

{{--
    Uso:
    <x-stitch-logo />               → nombre + aguja (navbar / footer)
    <x-stitch-logo :iconOnly="true" /> → solo aguja compacta (sidebar admin)
--}}

@php
    /* Aguja de coser — SVG inline, amarillo dorado */
    $needleSvg = '
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 80" fill="none">
        <!-- Cuerpo de la aguja -->
        <rect x="10" y="8" width="4" height="52" rx="2" fill="#F5C518"/>
        <!-- Ojo de la aguja (hueco oval) -->
        <ellipse cx="12" cy="12" rx="2.2" ry="3.5" fill="#F5C518" stroke="#c49a00" stroke-width="0.8"/>
        <ellipse cx="12" cy="12" rx="1" ry="1.8" fill="white"/>
        <!-- Punta de la aguja -->
        <polygon points="10,60 14,60 12,76" fill="#F5C518"/>
        <!-- Hilo -->
        <path d="M14 12 Q22 20 18 35 Q14 48 20 62" stroke="#9b7fc4" stroke-width="1.2" fill="none" stroke-linecap="round"/>
    </svg>';
@endphp

@if($iconOnly)
    {{-- Sidebar admin: aguja compacta --}}
    <div class="{{ $attributes->get('class') ?? '' }}"
         style="width: 28px; height: 42px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        {!! $needleSvg !!}
    </div>

@else
    {{-- Navbar / Footer: aguja + nombre de marca --}}
    <div class="{{ $attributes->get('class') ?? '' }}"
         style="display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0;">

        {{-- Aguja SVG --}}
        <div style="width: 18px; height: 44px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            {!! $needleSvg !!}
        </div>

        {{-- Texto de marca --}}
        <div style="display: flex; flex-direction: column; gap: 1px; line-height: 1;">
            <span style="font-size: 1.25rem; font-weight: 900; color: #4a1a6e; font-family: 'Inter', sans-serif; letter-spacing: -0.5px; white-space: nowrap;">
                Stitch <span style="color: #b8962e;">&amp;</span> Co.
            </span>
            <span style="font-size: 8.5px; font-weight: 700; color: #9b7fc4; text-transform: uppercase; letter-spacing: 0.14em; white-space: nowrap;">
                Mercería Online
            </span>
        </div>

    </div>
@endif
