@extends('layouts.app')

@section('title', 'Mi Perfil — Stitch & Co')

@section('content')

<div class="flex flex-col lg:flex-row gap-10">

    {{-- Sidebar --}}
    <aside class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-24">
            <div class="flex items-center gap-4 mb-6">
                <div class="size-14 rounded-full bg-primary/10 border-2 border-primary flex items-center justify-center text-primary font-black text-xl">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</h3>
                    <p class="text-xs font-medium text-primary">Cliente</p>
                </div>
            </div>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.index') ? 'bg-primary text-white' : 'text-slate-600 hover:bg-primary/5 hover:text-primary' }}">
                    <span class="material-symbols-outlined">person</span> Información Personal
                </a>
                <a href="{{ route('profile.orders') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.orders') ? 'bg-primary text-white' : 'text-slate-600 hover:bg-primary/5 hover:text-primary' }}">
                    <span class="material-symbols-outlined">shopping_bag</span> Mis Pedidos
                </a>
                <a href="{{ route('wishlist.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-primary/5 hover:text-primary font-medium transition-colors">
                    <span class="material-symbols-outlined">favorite</span> Lista de Deseos
                </a>
                <div class="border-t border-slate-100 mt-4 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-3 px-4 py-3 rounded-lg text-rose-500 hover:bg-rose-50 font-medium transition-colors">
                            <span class="material-symbols-outlined">logout</span> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    {{-- Main Content --}}
    <section class="flex-1 max-w-3xl">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Información Personal</h1>
            <p class="text-slate-500 mt-2">Gestiona los datos de tu cuenta.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700">Nombre</label>
                        <input name="nombre" type="text" value="{{ old('nombre', auth()->user()->nombre) }}"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 px-4 transition-all" required/>
                        @error('nombre')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700">Apellido</label>
                        <input name="apellido" type="text" value="{{ old('apellido', auth()->user()->apellido) }}"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 px-4 transition-all" required/>
                        @error('apellido')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold text-slate-700">Correo Electrónico</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3 text-slate-400 text-xl">mail</span>
                        <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 pl-12 pr-4 transition-all" required/>
                    </div>
                    @error('email')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700">C.I. (opcional)</label>
                        <input name="cedula_identidad" type="text" value="{{ old('cedula_identidad', auth()->user()->cedula_identidad) }}"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 px-4 transition-all"/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-slate-700">Teléfono (opcional)</label>
                        <input name="telefono" type="tel" value="{{ old('telefono', auth()->user()->telefono) }}"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 px-4 transition-all"/>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-bold text-slate-700">Nueva Contraseña <span class="text-slate-400 font-normal">(dejar en blanco para no cambiar)</span></label>
                    <input name="password" type="password" placeholder="Mínimo 8 caracteres"
                           class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary h-12 px-4 transition-all"/>
                    @error('password')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                    <button type="submit"
                            class="px-8 py-3 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:bg-primary-dark transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </section>

</div>

@endsection
