@extends('layouts.app')

@section('title', 'Tutoriales y Guías — Stitch & Co')

@section('content')

{{-- Header --}}
<div class="mb-12">
    <nav class="flex items-center gap-2 text-sm mb-8 text-slate-500">
        <a class="hover:text-primary" href="{{ route('home') }}">Inicio</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-slate-900 font-medium">Tutoriales y Guías</span>
    </nav>
    <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">Tutoriales y Guías de Costura</h1>
    <p class="text-lg text-slate-600 max-w-2xl leading-relaxed">
        Domina nuevas técnicas con nuestras guías expertas. Desde puntadas básicas hasta sastrería profesional.
    </p>
</div>

{{-- Category Filters --}}
<div class="flex items-center gap-3 overflow-x-auto pb-4 mb-10">
    <button class="whitespace-nowrap px-6 py-2.5 rounded-full bg-primary text-white font-semibold text-sm shadow-lg shadow-primary/25">
        Todo el Contenido
    </button>
    <button class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 font-medium text-sm hover:border-primary/50 hover:bg-primary/5 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">apparel</span> Costura
    </button>
    <button class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 font-medium text-sm hover:border-primary/50 hover:bg-primary/5 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">potted_plant</span> Bordado
    </button>
    <button class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 font-medium text-sm hover:border-primary/50 hover:bg-primary/5 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">auto_fix</span> Manualidades
    </button>
    <button class="whitespace-nowrap px-6 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 font-medium text-sm hover:border-primary/50 hover:bg-primary/5 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">school</span> Principiantes
    </button>
</div>

{{-- Tutorial Cards (static content) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
    @php
        $tutoriales = [
            ['titulo' => 'Puntada Básica para Principiantes', 'categoria' => 'Costura', 'nivel' => 'Principiante', 'duracion' => '15 min', 'icon' => 'apparel'],
            ['titulo' => 'Cómo Elegir el Hilo Correcto', 'categoria' => 'Materiales', 'nivel' => 'Principiante', 'duracion' => '10 min', 'icon' => 'straighten'],
            ['titulo' => 'Bordado Floral en Tela de Lino', 'categoria' => 'Bordado', 'nivel' => 'Intermedio', 'duracion' => '45 min', 'icon' => 'potted_plant'],
            ['titulo' => 'Técnica de Pespunte Invisible', 'categoria' => 'Costura', 'nivel' => 'Avanzado', 'duracion' => '30 min', 'icon' => 'visibility_off'],
            ['titulo' => 'Mantenimiento de tu Máquina de Coser', 'categoria' => 'Mantenimiento', 'nivel' => 'Todos', 'duracion' => '20 min', 'icon' => 'build'],
            ['titulo' => 'Patrones Básicos de Sastrería', 'categoria' => 'Sastrería', 'nivel' => 'Avanzado', 'duracion' => '60 min', 'icon' => 'design_services'],
        ];
    @endphp

    @foreach($tutoriales as $tut)
    <div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all">
        <div class="h-48 bg-primary/10 flex items-center justify-center relative overflow-hidden">
            <span class="material-symbols-outlined text-7xl text-primary/30 group-hover:scale-110 transition-transform duration-500">{{ $tut['icon'] }}</span>
            <div class="absolute top-4 left-4">
                <span class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">{{ $tut['categoria'] }}</span>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xs text-slate-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">schedule</span> {{ $tut['duracion'] }}
                </span>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    {{ $tut['nivel'] === 'Principiante' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $tut['nivel'] === 'Intermedio' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $tut['nivel'] === 'Avanzado' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $tut['nivel'] === 'Todos' ? 'bg-primary/10 text-primary' : '' }}">
                    {{ $tut['nivel'] }}
                </span>
            </div>
            <h3 class="font-bold text-lg text-slate-900 mb-3 group-hover:text-primary transition-colors">{{ $tut['titulo'] }}</h3>
            <button class="flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all">
                Ver tutorial <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- Newsletter CTA --}}
<section class="bg-primary/10 rounded-[2.5rem] p-12 flex items-center justify-between relative overflow-hidden">
    <div class="absolute -bottom-12 -right-12 size-64 bg-primary/20 rounded-full blur-3xl"></div>
    <div class="max-w-xl">
        <h3 class="text-3xl font-black text-slate-900 mb-4">¿Quieres más tutoriales exclusivos?</h3>
        <p class="text-slate-600 mb-8">Suscríbete y recibe nuevas guías en tu correo cada semana.</p>
        <div class="flex gap-2">
            <input class="flex-1 bg-white border-none rounded-xl px-6 py-4 text-sm focus:ring-2 focus:ring-primary shadow-sm"
                   placeholder="Tu correo electrónico" type="email"/>
            <button class="bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-dark shadow-lg shadow-primary/20 transition-all">
                Suscribirme
            </button>
        </div>
    </div>
</section>

@endsection
