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
    @stack('styles')
</head>
<body class="bg-background-light font-sans text-slate-900 min-h-screen">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 border-r border-slate-800">
        <div class="p-6 flex items-center gap-3">
            <x-stitch-logo size="w-6 h-8" textSize="text-lg" subTextSize="text-[8px]" />
            <div>
                <h1 class="text-lg font-bold leading-tight">Stitch &amp; Co</h1>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="{{ request()->routeIs('admin.products.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">inventory_2</span>
                Productos
            </a>
            <a href="{{ route('admin.offers.index') }}"
               class="{{ request()->routeIs('admin.offers.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">loyalty</span>
                Gestor de Ofertas
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="{{ request()->routeIs('admin.orders.*') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">shopping_cart</span>
                Pedidos
            </a>
            <a href="{{ route('admin.payments') }}"
               class="{{ request()->routeIs('admin.payments') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">account_balance_wallet</span>
                Verificar Pagos
            </a>
            <a href="{{ route('admin.shipping') }}"
               class="{{ request()->routeIs('admin.shipping') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">local_shipping</span>
                Logística y Despachos
            </a>
            <a href="{{ route('admin.clients') }}"
               class="{{ request()->routeIs('admin.clients') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">group</span>
                Clientes
            </a>
            <a href="{{ route('admin.comunidad') }}"
               class="{{ request()->routeIs('admin.comunidad') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">forum</span>
                Comunidad
            </a>
            <a href="{{ route('admin.stock-notifications') }}"
               class="{{ request()->routeIs('admin.stock-notifications') ? 'bg-primary/15 text-primary' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined">notifications_active</span>
                Avisos de Stock
            </a>
        </nav>

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

@stack('scripts')
</body>
</html>
