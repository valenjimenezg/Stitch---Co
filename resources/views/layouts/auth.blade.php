<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acceso') | Stitch &amp; Co</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary/10 via-purple-100 to-primary/20 font-sans text-slate-900 min-h-screen relative overflow-x-hidden">

    {{-- Header mínimo --}}
    <header class="border-b border-primary/10 bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center shrink-0">
                    <x-stitch-logo size="w-6 h-10" textSize="text-xl" subTextSize="text-[8px]" class="scale-[0.80] sm:scale-90 origin-left" />
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="{{ route('categories.show', 'telas') }}">Telas</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="{{ route('categories.show', 'hilos') }}">Hilos</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors" href="{{ route('categories.show', 'agujas') }}">Agujas</a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Decoraciones de fondo --}}
    <div class="fixed top-20 -left-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl -z-10"></div>
    <div class="fixed bottom-20 -right-20 w-96 h-96 bg-primary/10 rounded-full blur-3xl -z-10"></div>

    {{-- Contenido --}}
    <main class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="max-w-7xl mx-auto px-4 py-8 border-t border-primary/10 text-center text-slate-400 text-xs">
        © {{ date('Y') }} Stitch &amp; Co. Todos los derechos reservados. Diseñado para creadores apasionados.
    </footer>

    @stack('scripts')
</body>
</html>
