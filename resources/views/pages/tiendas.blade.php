@extends('layouts.app')

@section('title', 'Nuestras Tiendas — Stitch & Co')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 text-primary mb-6">
            <span class="material-symbols-outlined text-4xl">storefront</span>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Nuestras Tiendas</h1>
        <p class="text-lg text-slate-500">Visítanos en nuestras sucursales físicas para vivir la experiencia Stitch & Co de cerca.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        {{-- Store selector --}}
        <div class="space-y-6">

            {{-- Tienda Guanare --}}
            <div onclick="selectStore(this, 'guanare')"
                 data-store="guanare"
                 class="store-card selected-store relative cursor-pointer bg-white rounded-2xl border-2 border-primary shadow-sm p-8 transition-all duration-300 hover:shadow-md">

                {{-- Check indicator --}}
                <div class="selected-dot absolute top-6 right-6 size-6 rounded-full border-[6px] border-primary transition-all duration-300"></div>

                <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl text-primary">storefront</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h2 class="text-2xl font-black text-slate-900">Stitch & Co Guanare</h2>
                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-sm font-bold text-emerald-600 uppercase tracking-widest">Abierto</span>
                            </div>
                        </div>
                        <p class="text-sm font-bold text-emerald-600 mb-4">Abierto hoy hasta las 6:30 PM</p>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-slate-400 mt-0.5 text-xl">location_on</span>
                                <p class="text-slate-600">Av. 23 e/ Calles 15 y 16<br>Guanare, Venezuela 3350</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-xl">phone</span>
                                <p class="text-slate-600">+58 412-1234567</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Retiro en Tienda
                            </span>
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Delivery a Domicilio
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tienda Acarigua --}}
            <div onclick="selectStore(this, 'acarigua')"
                 data-store="acarigua"
                 class="store-card relative cursor-pointer bg-white rounded-2xl border-2 border-slate-100 shadow-sm p-8 transition-all duration-300 hover:border-slate-200 hover:shadow-md">

                {{-- Check indicator (hidden by default) --}}
                <div class="selected-dot absolute top-6 right-6 size-6 rounded-full border-2 border-slate-200 opacity-0 transition-all duration-300"></div>

                <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl text-slate-400">storefront</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h2 class="text-2xl font-black text-slate-900">Stitch & Co Guanare (Mall)</h2>
                            <div class="flex items-center gap-2">
                                <span class="size-2.5 rounded-full bg-slate-400"></span>
                                <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Cerrado</span>
                            </div>
                        </div>
                        <p class="text-sm font-bold text-slate-500 mb-4">Abre mañana a las 9:00 AM</p>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-slate-400 mt-0.5 text-xl">location_on</span>
                                <p class="text-slate-600">CC Llano Mall, Nivel 1, Local 45<br>Guanare, Venezuela</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-xl">phone</span>
                                <p class="text-slate-600">+58 424-7654321</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Pickup Expreso
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Confirm button --}}
            <div class="mt-8">
                <button onclick="confirmStore()" class="w-full bg-slate-900 hover:bg-black text-white font-black px-10 py-5 rounded-2xl tracking-widest text-sm uppercase transition-all active:scale-[0.98] shadow-xl shadow-slate-900/20">
                    Confirmar Tienda
                </button>
                <p class="text-xs text-slate-400 mt-4 font-medium text-center" id="store-confirmed-text"></p>
            </div>

        </div>

        {{-- Google Maps Viewport --}}
        <div class="lg:sticky lg:top-8 h-[700px] w-full bg-slate-100 rounded-3xl overflow-hidden border-4 border-white shadow-2xl relative">
            <div id="map-loader" class="absolute inset-0 flex items-center justify-center bg-slate-50 z-0">
                <span class="material-symbols-outlined text-slate-300 text-6xl animate-pulse">map</span>
            </div>
            <iframe id="store-map" 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.564756621067!2d-69.8339191!3d9.0435165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e7cafe031cb4893%3A0xe549927ef5af2e17!2sGuanare%2C%20Portuguesa%2C%20Venezuela!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus" 
                class="w-full h-full relative z-10" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const maps = {
        'guanare': 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.564756621067!2d-69.8339191!3d9.0435165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e7cafe031cb4893%3A0xe549927ef5af2e17!2sGuanare%2C%20Portuguesa%2C%20Venezuela!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus',
        'acarigua': 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.564756621067!2d-69.8339191!3d9.0435165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e7cafe031cb4893%3A0xe549927ef5af2e17!2sGuanare%2C%20Portuguesa%2C%20Venezuela!5e0!3m2!1sen!2sus!4v1700000000000!5m2!1sen!2sus'
    };

    function selectStore(el, storeId) {
        document.getElementById('store-map').src = maps[storeId];

        document.querySelectorAll('.store-card').forEach(card => {
            card.classList.remove('selected-store', 'border-primary', 'bg-primary/5');
            card.classList.add('border-slate-100');
            const dot = card.querySelector('.selected-dot');
            if (dot) {
                dot.classList.remove('border-[6px]', 'border-primary');
                dot.classList.add('border-2', 'border-slate-200', 'opacity-0');
            }
        });

        el.classList.add('selected-store', 'border-primary');
        el.classList.remove('border-slate-100');
        const dot = el.querySelector('.selected-dot');
        if (dot) {
            dot.classList.add('border-[6px]', 'border-primary');
            dot.classList.remove('border-2', 'border-slate-200', 'opacity-0');
        }
    }

    function confirmStore() {
        const selected = document.querySelector('.store-card.selected-store');
        if (!selected) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona una tienda',
                text: 'Por favor elige una de nuestras sucursales antes de confirmar.',
                confirmButtonColor: '#8b52ff',
            });
            return;
        }
        const name = selected.querySelector('h2').textContent.trim();
        Swal.fire({
            icon: 'success',
            title: '¡Tienda Guardada!',
            text: `Has seleccionado ${name} para tus próximos retiros.`,
            confirmButtonColor: '#8b52ff',
        });
    }
</script>
@endpush
@endsection
