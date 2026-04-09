@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

{{-- Hero Carousel --}}
<section class="mt-8 relative overflow-hidden rounded-2xl h-[480px] group bg-black">
    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105">
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('video/coser.mp4') }}" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>
        <div class="relative z-10 w-full h-full bg-gradient-to-r from-black/60 to-transparent flex flex-col justify-center px-20">
            <span class="text-primary bg-white/90 self-start px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Nueva Colección</span>
            <h2 class="text-5xl font-black text-white max-w-xl leading-tight mb-6">Crea con Pasión, <br/>Cose con Amor</h2>
            <p class="text-white/80 text-lg max-w-md mb-8">Descubre las mejores texturas y colores para tus proyectos de esta temporada. Calidad premium importada.</p>
            <a href="{{ route('categories.show', 'novedades') }}"
               class="bg-primary text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-primary-dark transition-all self-start shadow-xl shadow-primary/30">
                Comprar Ahora
            </a>
        </div>
    </div>
</section>

{{-- Categorías --}}
<section class="mt-16">
    <div class="text-center mb-10">
        <h3 class="text-3xl font-bold text-slate-900">Categorías Principales</h3>
        <p class="text-slate-500 mt-2">Todo lo que necesitas para tu próximo proyecto</p>
    </div>
    <div class="grid grid-cols-4 gap-6">
        @php
            $categorias = [
                ['slug' => 'lanas',      'nombre' => 'Lanas',      'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAOhHZ6wZRDMN0gYXTCeDZUQIkr2QxuiJnPGxtYnfNOahjljb9jZfGmzoYs87hI-xjrm5oCDT0JBQ28FVSZ8X1JycfdCqJMMnTpX0LVDvdZqX9FwFmpFcxyq2sJcP0JoWR51tnHI9qXPd8HbxdW0Oz7wOEsNughiMm6_59l5Dhcjy1zRMw12oJNqD-CUIWGFSwcKpx4x2hXKG-VW_Jo4DKfvnrFFxGUj5qiRR5XteZ1Oeu6CM8J_WYZ137n_vxYCetAS9QlQpP2FWXO'],
                ['slug' => 'telas',      'nombre' => 'Telas',      'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCmQ8hetkDBFWH-k18347gr2nJeXBIlvskXO3FJ7HGxr0uFlsQhLPKVW5KAf4vw-YlxsMbp4xhQof0dKxHGUECnyoxks8j9Ob8LHtVMd7hONk7OMVm9HbOteF9M6apRIe1Rp9Sv-uQn06krZdgLQM7ehlkXa83d2Q_IAN68Sd4kyeku-58mxLtmZV_ILxzgLdBcK8RTwixjTcF4QCF9PEh1CAA1WXu7Jxtcc-d-E3eZipIJrCJX-SyRZl_hEokPzHLdCTJWhAGeP0VP'],
                ['slug' => 'merceria',   'nombre' => 'Botones',    'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBgWvTuC4r2ynDFQSFGRnBNTDSGybIozuThBm8rMD4CE1gASmv-ufBDnWTuW7fYaghyb0cT_Bm6Wsuwf2n-yuIqaS3_7LDIR37hN-91rk7uIrv9jIHGJLOitul7CyDwp_POzBDspPpW8Bdb99Vo5EYK_J4_yLiJI2FndYEqJguWcDngNkOern4J1yDbDCBc4qgbZizN4GKUsrD7GE3qABfC9FUmwkeNf45rrW1HcE_nbn_wM4NIZes_W7zW2KdLVm-y1opHcb9upY2c'],
                ['slug' => 'accesorios', 'nombre' => 'Accesorios', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBDuteQULBaKOTJDuHx4sH4TTStXOu9paM0YeYrYdUpWbq7LipnW7FcygBTXQk6OKOuFGxDsSQmYU31ATQ3sBRy_uMe0z105cKfWqTpgHALIBRQlDehsdWkg7jkAln97KF7F3YMLSG6KlyuryiiQTqxmlKnAsyA-va5irG8eDZaxjucZboTGTyF3T5zkanABBjQqPK8s49eC7axx7TlWzl2-MNh2dt1jhHNWjc8je5zZzvhrfiIJsrdvWy15NdOM9BccBX4Ikg4WN4M'],
            ];
        @endphp
        @foreach($categorias as $cat)
            <a href="{{ route('categories.show', $cat['slug']) }}" class="group cursor-pointer">
                <div class="aspect-square rounded-2xl overflow-hidden mb-4 relative">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         src="{{ $cat['img'] }}" alt="{{ $cat['nombre'] }}"/>
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition-colors"></div>
                </div>
                <h4 class="text-center font-bold text-lg group-hover:text-primary transition-colors">{{ $cat['nombre'] }}</h4>
            </a>
        @endforeach
    </div>
</section>

{{-- Ofertas Destacadas --}}
@if(isset($ofertas) && $ofertas->count() > 0)
<section class="mt-20">
    <div class="text-center mb-10">
        <h3 class="text-4xl font-black text-black tracking-tight">¡Ofertas por Tiempo Limitado!</h3>
        <p class="text-slate-500 mt-2 font-medium">Aprovecha descuentos exclusivos en tus materiales favoritos</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($ofertas as $variante)
            <x-product-card :variante="$variante"/>
        @endforeach
    </div>
</section>
@endif

{{-- Productos Destacados --}}
<section class="mt-20">
    <div class="text-center mb-10">
        <h3 class="text-3xl font-bold text-slate-900">Productos Destacados</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($destacados as $variante)
            <x-product-card :variante="$variante"/>
        @empty
            <div class="col-span-4 text-center text-slate-400 py-12">
                <span class="material-symbols-outlined text-5xl mb-2 block">inventory_2</span>
                Pronto habrá productos disponibles.
            </div>
        @endforelse
    </div>
</section>

{{-- Newsletter --}}
<section class="mt-24 bg-primary/10 rounded-[2.5rem] p-12 flex items-center justify-between relative overflow-hidden">
    <div class="absolute -bottom-12 -right-12 size-64 bg-primary/20 rounded-full blur-3xl"></div>
    <div class="max-w-xl">
        <h3 class="text-4xl font-black text-slate-900 mb-4">¡Que no te falte el hilo! Únete a Stitch & Co</h3>
        <p class="text-slate-600 text-lg mb-8">Suscríbete para recibir alertas cuando lleguen nuevos hilos, cintas, botones y herramientas, además de cupones de descuento exclusivos para tus proyectos de costura.</p>
        
        <div id="newsletter-success" class="bg-emerald-100 text-emerald-800 p-4 rounded-xl mb-6 font-medium flex items-center gap-3 hidden transition-opacity duration-500 opacity-0">
            <span class="material-symbols-outlined">check_circle</span>
            <span>¡Listo! Ya eres parte de Stitch & Co.</span>
        </div>

        <form id="newsletter-form" onsubmit="handleNewsletterSubmit(event)" class="flex flex-col gap-2 relative z-10 transition-opacity duration-500">
            @csrf
            <div class="flex gap-2 w-full">
                <input name="email" id="newsletter-email" class="flex-1 bg-white border-none rounded-xl px-6 py-4 text-sm focus:ring-2 focus:ring-primary shadow-sm"
                       placeholder="Tu correo electrónico" type="email" required/>
                <button type="submit" id="newsletter-submit-btn" class="bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-dark shadow-lg shadow-primary/20 transition-all shrink-0">
                    Suscribirme
                </button>
            </div>
            <p id="newsletter-error" class="hidden text-rose-500 text-sm mt-1 mx-2 font-medium flex items-center gap-1"></p>
        </form>
    </div>
</section>

@push('scripts')
<script>
    function handleNewsletterSubmit(e) {
        e.preventDefault();
        const form = document.getElementById('newsletter-form');
        const emailInput = document.getElementById('newsletter-email');
        const submitBtn = document.getElementById('newsletter-submit-btn');
        const errorEl = document.getElementById('newsletter-error');
        const successEl = document.getElementById('newsletter-success');

        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Enviando...';
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        errorEl.classList.add('hidden');
        errorEl.innerHTML = '';

        fetch('{{ route('newsletter.subscribe') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: emailInput.value })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.style.opacity = '0';
                setTimeout(() => {
                    form.classList.add('hidden');
                    successEl.classList.remove('hidden');
                    successEl.classList.add('flex');
                    setTimeout(() => successEl.style.opacity = '1', 50);
                }, 500);
            } else if (data.errors && data.errors.email) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Suscribirme';
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                errorEl.innerHTML = '<span class="material-symbols-outlined text-[14px]">error</span> ' + data.errors.email[0];
                errorEl.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Suscribirme';
            submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
        });
    }
</script>
@endpush

@endsection
