<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <title>@yield('title', 'Stitch &amp; Co') | Stitch &amp; Co</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeletion(event, title, text) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: title || '¿Estás seguro?',
                text: text || "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    title: 'text-xl font-bold text-slate-800',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg font-bold px-6 outline-none',
                    cancelButton: 'rounded-lg font-bold px-6 outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background-light text-slate-900 font-sans">

    {{-- Top Utility Bar --}}
    <div class="bg-primary text-white border-b border-primary/20">
        <div class="max-w-[1280px] mx-auto px-6 py-2 flex items-center justify-between text-xs font-bold tracking-wide">
            
            {{-- Contacto y Redes Sociales --}}
            <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                <!-- Redes -->
                <div class="flex items-center gap-3">
                    <a class="text-white hover:text-white/80 transition-colors" href="#" aria-label="Facebook">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                    </a>
                    <a class="text-white hover:text-white/80 transition-colors" href="#" aria-label="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12.2 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                    </a>
                    <a class="text-white hover:text-white/80 transition-colors" href="#" aria-label="Pinterest">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 384 512"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-33-26.1-58.8-57-58.8-38.4 0-66.6 32.7-66.6 74.1 0 21.3 6.9 49.5 21.3 63.9l-41.4 171.3c-14.4 56.4-5.1 124.8-5.1 124.8s16.5-16.2 27.6-59.4c2.4-9.3 15.9-63.3 27-105.6 12.6 24 43.5 45 76.5 45 80.1 0 148.5-69.6 148.5-168.3C384 94.8 296.4 6.5 204 6.5z"/></svg>
                    </a>
                </div>
                
                <div class="w-px h-3 bg-white/30 hidden sm:block"></div>
                
                <div class="flex items-center gap-1.5 cursor-pointer hover:text-white/80 transition-colors">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 512 512"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
                    <span>+58 424 565 9154</span>
                </div>
                
                <div class="flex items-center gap-1.5 cursor-pointer hover:text-white/80 transition-colors">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 512 512"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
                    <span>info@stitchandco.com.ve</span>
                </div>
            </div>

            {{-- Logout Option on the Right (Removed for cleaner look) --}}
            
        </div>
    </div>

    {{-- Main Header --}}
    <header class="bg-white border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-[1280px] mx-auto px-6 py-4 flex items-center justify-between gap-8">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center shrink-0">
                <x-stitch-logo />
            </a>

            {{-- Search Bar --}}
            <div class="flex-1 max-w-2xl relative">
                <form action="{{ route('search.index') }}" method="GET" class="relative group z-40">
                    <button type="submit" class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-primary hover:text-primary transition-colors cursor-pointer outline-none border-none bg-transparent">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                    <input
                        name="q"
                        value="{{ request('q') }}"
                        class="block w-full pl-12 pr-4 py-2.5 bg-slate-100 border-transparent focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl transition-all placeholder:text-slate-500 text-sm"
                        placeholder="Buscar hilos, telas, agujas..."
                        type="search"
                        id="search-input"
                        autocomplete="off"
                    />
                </form>
                
                {{-- Dropdown de resultados (Live Search) --}}
                <div id="search-results-container" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden hidden z-50 max-h-[400px] overflow-y-auto">
                    <ul id="search-results-list" class="divide-y divide-slate-50">
                        <!-- Resultados inyectados por JS -->
                    </ul>
                    <div id="search-results-empty" class="p-8 text-center text-slate-500 text-sm hidden">
                        No se encontraron productos que coincidan con tu búsqueda.
                    </div>
                    <div id="search-results-loading" class="p-8 text-center text-slate-400 text-sm hidden">
                        <span class="material-symbols-outlined animate-spin text-3xl text-primary block mx-auto mb-3">progress_activity</span>
                        <p class="font-medium">Buscando productos...</p>
                    </div>
                </div>
            </div>

            {{-- User Actions --}}
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 pr-4 pl-1 hover:bg-slate-50 rounded-full transition-all border border-transparent hover:border-slate-200 group">
                        <div class="size-10 bg-white border border-slate-100 rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-all text-[22px] select-none">
                            👩🏻‍🦰
                        </div>
                        <div class="flex flex-col text-left py-1">
                            <span class="text-[14px] font-medium text-slate-800 leading-none mb-1">Hola, {{ strtok(auth()->user()->nombre, ' ') }}</span>
                            <span class="text-[10px] font-bold text-slate-600 tracking-wider uppercase leading-none flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px] text-yellow-400" style="font-variation-settings: 'FILL' 1;">star</span>
                                Cliente Estrella
                            </span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 p-2.5 text-slate-600 hover:bg-slate-100 hover:text-primary rounded-lg transition-colors font-medium text-sm">
                        <span class="material-symbols-outlined">person</span>
                        <span>Acceder</span>
                    </a>
                @endauth

                <a href="{{ route('wishlist.index') }}" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors relative">
                    <span class="material-symbols-outlined">favorite</span>
                    @auth
                        @if(auth()->user()->listaDeseos()->count() > 0)
                            <span class="absolute top-1.5 right-1.5 size-4 bg-primary text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
                                {{ auth()->user()->listaDeseos()->count() }}
                            </span>
                        @endif
                    @endauth
                </a>

                <a href="{{ route('cart.index') }}" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors relative">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    @php
                        $displayCount = $globalCartCount > 99 ? '99+' : $globalCartCount;
                        $badgeClasses = $globalCartCount > 9 ? 'min-w-[20px] px-1 h-4 text-[9px]' : 'size-4 text-[10px]';
                    @endphp
                    @if($globalCartCount > 0)
                        <span class="cart-badge absolute top-1.5 right-0.5 {!! $badgeClasses !!} bg-primary text-white font-bold flex items-center justify-center rounded-full border-2 border-white shadow-sm transition-all duration-200">
                            {{ $displayCount }}
                        </span>
                    @else
                        <span class="cart-badge absolute top-1.5 right-0.5 size-4 bg-primary text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white shadow-sm transition-all duration-200 hidden">
                            0
                        </span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="bg-primary text-white">
            <div class="max-w-[1280px] mx-auto px-6">
                <ul class="flex items-center justify-center gap-2 font-medium text-sm">
                    <li><a class="px-5 py-3 inline-block hover:bg-white/10 transition-colors border-b-2 border-transparent hover:border-white" href="{{ route('categories.show', 'tejido') }}">Tejido</a></li>
                    <li><a class="px-5 py-3 inline-block hover:bg-white/10 transition-colors border-b-2 border-transparent hover:border-white" href="{{ route('categories.show', 'costura') }}">Costura</a></li>
                    <li><a class="px-5 py-3 inline-block hover:bg-white/10 transition-colors border-b-2 border-transparent hover:border-white" href="{{ route('categories.show', 'manualidades') }}">Manualidades</a></li>
                    <li><a class="px-5 py-3 inline-block hover:bg-white/10 transition-colors border-b-2 border-transparent hover:border-white text-yellow-300 font-bold italic" href="{{ route('offers.index') }}">Ofertas %</a></li>
                </ul>
            </div>
        </nav>
    </header>


    {{-- Page Content --}}
    <main class="max-w-[1280px] mx-auto px-6 pb-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-8">
        <div class="max-w-[1280px] mx-auto px-6 grid grid-cols-5 gap-12 mb-16">
            <div class="col-span-2">
                <div class="flex items-center mb-6">
                    <x-stitch-logo size="w-8 h-10" textSize="text-2xl" subTextSize="text-[10px]" class="grayscale opacity-90 hover:grayscale-0 hover:opacity-100 transition-all"/>
                </div>
                <p class="text-sm leading-relaxed mb-6 pr-12">
                    Desde hace más de 15 años, somos el aliado perfecto para diseñadores, costureras y mentes creativas. Ofreciendo lo mejor en insumos de mercería y textiles.
                    <br><br>
                    <span class="flex items-center gap-2 font-bold text-white/90">
                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                        Guanare, Venezuela
                    </span>
                </p>
                <div class="flex gap-4">
                    <a class="size-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors" href="#"><span class="material-symbols-outlined">brand_awareness</span></a>
                    <a class="size-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors" href="#"><span class="material-symbols-outlined">camera</span></a>
                    <a class="size-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-primary transition-colors" href="#"><span class="material-symbols-outlined">chat</span></a>
                </div>
            </div>
            <div>
                <h6 class="text-white font-bold mb-6">Tienda</h6>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('categories.show', 'lanas') }}">Lanas y Hilos</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('categories.show', 'telas') }}">Telas y Retazos</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('categories.show', 'merceria') }}">Mercería y Botones</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('categories.show', 'kits') }}">Kits de Inicio</a></li>
                </ul>
            </div>
            <div>
                <h6 class="text-white font-bold mb-6">Compañía</h6>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('pages.about') }}">Sobre Nosotros</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('contact.index') }}">Contacto</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('pages.faq') }}">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div>
                <h6 class="text-white font-bold mb-6">Legal</h6>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="#">Términos de Uso</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Política de Privacidad</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Envíos y Devoluciones</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-[1280px] mx-auto px-6 pt-8 border-t border-white/5 flex items-center justify-between text-xs">
            <p>© {{ date('Y') }} Stitch &amp; Co Haberdashery. Todos los derechos reservados.</p>
            <div class="flex items-center gap-4">
                <span class="text-white/20">Pago seguro:</span>
                <span class="material-symbols-outlined">credit_card</span>
                <span class="material-symbols-outlined">account_balance</span>
                <span class="material-symbols-outlined">qr_code_2</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        window.bcvRate = {{ function_exists('bcv_rate') ? bcv_rate() : 1 }};
    </script>
    <script src="{{ asset('js/cart.js') }}?v=1.2"></script>
    
    @if(session('clear_cart'))
    <script>
        localStorage.removeItem('stitch_cart');
        if (typeof Cart !== 'undefined') Cart.updateBadge();
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            if(!searchInput) return;

            const resultsContainer = document.getElementById('search-results-container');
            const resultsList = document.getElementById('search-results-list');
            const emptyState = document.getElementById('search-results-empty');
            const loadingState = document.getElementById('search-results-loading');
            let debounceTimer;

            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.trim();
                
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    resultsContainer.classList.add('hidden');
                    return;
                }

                // Show loading state immediately
                resultsContainer.classList.remove('hidden');
                resultsList.innerHTML = '';
                resultsList.classList.add('hidden');
                emptyState.classList.add('hidden');
                loadingState.classList.remove('hidden');

                debounceTimer = setTimeout(() => {
                    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
                    fetch(`${baseUrl}/api/search?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            loadingState.classList.add('hidden');
                            
                            if (data.length === 0) {
                                emptyState.classList.remove('hidden');
                            } else {
                                resultsList.classList.remove('hidden');
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.innerHTML = `
                                        <a href="${item.url}" class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors group">
                                            <div class="flex items-center gap-4">
                                                <div class="size-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-hover:bg-primary/5 transition-colors overflow-hidden border border-slate-100 shrink-0">
                                                    ${item.miniatura 
                                                        ? `<img src="${item.miniatura}" alt="${item.nombre}" class="w-full h-full object-cover">`
                                                        : `<span class="material-symbols-outlined text-[1.4rem]">inventory_2</span>`
                                                    }
                                                </div>
                                                <div class="flex flex-col">
                                                    <h4 class="text-[13px] font-bold text-slate-900 group-hover:text-primary transition-colors leading-tight line-clamp-2">${item.nombre}</h4>
                                                    <span class="text-[11px] text-slate-400 font-medium tracking-wide mt-1 uppercase">Ref: $${item.precio_usd}</span>
                                                </div>
                                            </div>
                                            <span class="text-sm font-black text-slate-900 bg-primary/5 px-3 py-1.5 rounded-lg border border-primary/10 ml-4 shrink-0">${item.precio}</span>
                                        </a>
                                    `;
                                    resultsList.appendChild(li);
                                });
                            }
                        })
                        .catch(err => {
                            loadingState.classList.add('hidden');
                            console.error('Error en el buscador en vivo:', err);
                        });
                }, 300);
            });

            // Cerrar el dropdown al hacer click fuera del contenedor
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });
            
            // Volver a mostrar los resultados al hacer foco y tener algo escrito
            searchInput.addEventListener('focus', () => {
                if (searchInput.value.trim().length >= 2) {
                    resultsContainer.classList.remove('hidden');
                }
            });
        });
    </script>

    <x-quick-view-modal />
    <x-whatsapp-button />
</body>
</html>
