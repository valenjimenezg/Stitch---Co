<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Stitch &amp; Co Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    <style>
        .bg-primary { background-color: #9333ea !important; }
        .text-primary { color: #9333ea !important; }
        .border-primary { border-color: #9333ea !important; }
        .hover\:bg-primary-dark:hover { background-color: #7e22ce !important; }
        .hover\:text-primary:hover { color: #9333ea !important; }
        .bg-primary\/5 { background-color: rgba(147, 51, 234, 0.05) !important; }
        .bg-primary\/10 { background-color: rgba(147, 51, 234, 0.1) !important; }
        .bg-primary\/15 { background-color: rgba(147, 51, 234, 0.15) !important; }
        .bg-primary\/20 { background-color: rgba(147, 51, 234, 0.2) !important; }
        .hover\:bg-primary\/5:hover { background-color: rgba(147, 51, 234, 0.05) !important; }
        .shadow-primary\/20 { box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.2) !important; }
        .focus\:ring-primary:focus, .focus\:border-primary:focus {
            border-color: #9333ea !important;
            box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2) !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light font-sans text-slate-900 min-h-screen">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 border-r border-slate-800">
        <div class="p-6 flex items-center gap-3">
            <x-stitch-logo size="w-6 h-8" :iconOnly="true" />
            <div>
                <h1 class="text-lg font-bold leading-tight">Stitch &amp; Co</h1>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Admin Panel</p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar">
            <nav class="px-4 py-4 space-y-6">
                
                {{-- MÓDULO COMERCIAL --}}
                <div>
                    <p class="px-3 text-[10px] font-black uppercase text-slate-500 tracking-widest mb-2">Comercial</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">dashboard</span>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.orders.index') }}"
                           class="{{ request()->routeIs('admin.orders.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            Pedidos / Ventas
                        </a>
                        <a href="{{ route('admin.payments') }}"
                           class="{{ request()->routeIs('admin.payments') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                            Auditoría de Pagos
                        </a>
                        <a href="{{ route('admin.offers.index') }}"
                           class="{{ request()->routeIs('admin.offers.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">loyalty</span>
                            Gestor de Ofertas
                        </a>
                    </div>
                </div>

                {{-- MÓDULO ALMACÉN & COMPRAS --}}
                <div>
                    <p class="px-3 text-[10px] font-black uppercase text-slate-500 tracking-widest mb-2">Almacén & Inventario</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.products.index') }}"
                           class="{{ request()->routeIs('admin.products.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">inventory_2</span>
                            Catálogo de Productos
                        </a>
                        <a href="{{ route('admin.proveedores.index') }}"
                           class="{{ request()->routeIs('admin.proveedores.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">factory</span>
                            Proveedores y Compras
                        </a>
                        <a href="{{ route('admin.inventario-logs') }}"
                           class="{{ request()->routeIs('admin.inventario-logs') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">history</span>
                            Kardex (Trazabilidad)
                        </a>
                        <a href="{{ route('admin.stock-notifications') }}"
                           class="{{ request()->routeIs('admin.stock-notifications') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">warning</span>
                            Alertas de Quiebre
                        </a>
                    </div>
                </div>

                {{-- MÓDULO LOGÍSTICA & CRM --}}
                <div>
                    <p class="px-3 text-[10px] font-black uppercase text-slate-500 tracking-widest mb-2">Logística & Clientes</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.shipping') }}"
                           class="{{ request()->routeIs('admin.shipping') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">local_shipping</span>
                            Rutas Generales
                        </a>
                        <a href="{{ route('admin.clients') }}"
                           class="{{ request()->routeIs('admin.clients') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">group</span>
                            Directorio Clientes
                        </a>
                        <a href="{{ route('admin.comunidad') }}"
                           class="{{ request()->routeIs('admin.comunidad') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                            <span class="material-symbols-outlined">forum</span>
                            Comunidad
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 p-2">
                <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center border border-primary/30">
                    <span class="material-symbols-outlined text-primary">person</span>
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
                    <p class="text-xs text-slate-400 truncate">Administrador</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-white" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-xl">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Area --}}
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Admin Top Bar --}}
        <header class="h-16 flex items-center justify-between px-8 bg-white border-b border-slate-200">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-full max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/50 placeholder:text-slate-400"
                           placeholder="Buscar pedidos, productos o clientes..."
                           type="text"/>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg relative">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="h-6 w-px bg-slate-200 mx-2"></div>
                <a href="{{ route('admin.products.create') }}"
                   class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Nuevo Producto
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="flex-1 overflow-y-auto p-8 space-y-8">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

{{-- Fallback CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Lógica de Búsqueda Instantánea para Panel Admin --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buscadores = document.querySelectorAll('input[placeholder*="Buscar"]');
        
        buscadores.forEach(buscador => {
            // Evitamos que recargue la página si el viejo código tenía 'onchange'
            if(buscador.hasAttribute('onchange')) {
                buscador.removeAttribute('onchange');
            }

            buscador.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                const filasTabla = document.querySelectorAll('table tbody tr');
                
                filasTabla.forEach(fila => {
                    // Ignorar fila de "Sin resultados" que usa colspan
                    if(fila.querySelector('td[colspan]')) return;
                    
                    const textoFila = fila.textContent.toLowerCase();
                    if(textoFila.includes(term)) {
                        fila.style.display = '';
                        fila.classList.add('animate-fade-in'); // pequeño destello de aparición
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>
