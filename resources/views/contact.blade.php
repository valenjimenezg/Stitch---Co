@extends('layouts.app')

@section('title', 'Contacto — Stitch & Co')

@section('content')

{{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-3xl min-h-[300px] flex items-center px-12 mb-12">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-primary/5 to-transparent backdrop-blur-3xl z-0"></div>
    <div class="absolute -top-24 -right-24 size-96 bg-primary/20 rounded-full blur-3xl z-0"></div>
    <div class="absolute top-1/2 -left-24 size-64 bg-secondary/20 rounded-full blur-3xl z-0"></div>
    
    <div class="relative z-10 max-w-2xl">
        <nav class="flex items-center gap-2 mb-6 text-sm font-semibold">
            <a class="text-slate-500 hover:text-primary transition-colors" href="{{ route('home') }}">Inicio</a>
            <span class="text-slate-300">/</span>
            <span class="text-primary">Contacto</span>
        </nav>
        <h1 class="text-slate-900 text-6xl font-black leading-tight tracking-tight mb-4">Contáctanos</h1>
        <p class="text-slate-600 text-lg max-w-md">Nuestro equipo está listo para ayudarte con cualquier consulta sobre productos, pedidos o talleres.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

    {{-- Left: Contact Details --}}
    <div class="lg:col-span-5 flex flex-col gap-4">
        <div class="flex gap-4 p-5 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 to-white/50 backdrop-blur-xl shadow-sm relative overflow-hidden group hover:border-primary/40 transition-colors">
            <div class="absolute -right-4 -bottom-4 size-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary shrink-0">
                <span class="material-symbols-outlined">location_on</span>
            </div>
            <div>
                <h3 class="text-slate-900 text-lg font-bold">Visítanos</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Av. 23 e/ Calles 15 y 16<br/>Guanare, Venezuela 3350</p>
            </div>
        </div>

        <div class="flex gap-4 p-5 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 to-white/50 backdrop-blur-xl shadow-sm relative overflow-hidden group hover:border-primary/40 transition-colors">
            <div class="absolute -right-4 -bottom-4 size-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary shrink-0">
                <span class="material-symbols-outlined">call</span>
            </div>
            <div>
                <h3 class="text-slate-900 text-lg font-bold">Llámanos</h3>
                <p class="text-slate-600 text-sm">+58 424 565 9154</p>
                <p class="text-slate-500 text-xs mt-1">Lunes a Viernes, 8am – 6:30pm</p>
            </div>
        </div>

        <div class="flex gap-4 p-5 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 to-white/50 backdrop-blur-xl shadow-sm relative overflow-hidden group hover:border-primary/40 transition-colors">
            <div class="absolute -right-4 -bottom-4 size-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary shrink-0">
                <span class="material-symbols-outlined">mail</span>
            </div>
            <div>
                <h3 class="text-slate-900 text-lg font-bold">Escríbenos</h3>
                <p class="text-slate-600 text-sm">hola@stitchco.com.ve</p>
            </div>
        </div>

        <div class="flex gap-4 p-5 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 to-white/50 backdrop-blur-xl shadow-sm relative overflow-hidden group hover:border-primary/40 transition-colors">
            <div class="absolute -right-4 -bottom-4 size-24 bg-primary/10 rounded-full blur-xl group-hover:bg-primary/20 transition-colors"></div>
            <div class="flex items-center justify-center size-12 rounded-lg bg-primary/10 text-primary shrink-0">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            <div>
                <h3 class="text-slate-900 text-lg font-bold">Horarios</h3>
                <div class="flex justify-between w-full max-w-[180px] text-sm mt-1">
                    <span class="text-slate-500">Lun – Vie</span>
                    <span class="text-slate-700 font-medium">9am – 6pm</span>
                </div>
                <div class="flex justify-between w-full max-w-[180px] text-sm">
                    <span class="text-slate-500">Sábado</span>
                    <span class="text-slate-700 font-medium">10am – 4pm</span>
                </div>
                <div class="flex justify-between w-full max-w-[180px] text-sm">
                    <span class="text-slate-500">Domingo</span>
                    <span class="text-slate-700 font-medium">Cerrado</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Contact Form --}}
    <div class="lg:col-span-7">
        <div class="bg-gradient-to-br from-primary/5 to-white/50 p-10 rounded-3xl shadow-xl border border-primary/20 backdrop-blur-xl relative overflow-hidden">
            <div class="absolute -top-32 -right-32 size-64 bg-primary/20 rounded-full blur-3xl z-0"></div>
            <div class="relative z-10">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Envíanos un Mensaje</h2>
            <p class="text-slate-500 mb-8">Completa el formulario y te responderemos en menos de 24 horas.</p>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700">Nombre Completo</label>
                        <input name="nombre" type="text" value="{{ old('nombre') }}" placeholder="María González"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-3 px-4" required/>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold text-slate-700">Correo Electrónico</label>
                        <input name="email" type="email" value="{{ old('email') }}" placeholder="maria@ejemplo.com"
                               class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-3 px-4" required/>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Asunto</label>
                    <select name="asunto" class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-3 px-4">
                        <option>Consulta General</option>
                        <option>Soporte de Pedido</option>
                        <option>Información de Productos</option>
                        <option>Ventas al Mayor</option>
                        <option>Otro</option>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-700">Tu Mensaje</label>
                    <textarea name="mensaje" rows="6" placeholder="¿En qué podemos ayudarte hoy?"
                              class="rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-3 px-4 resize-none" required>{{ old('mensaje') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white font-bold py-4 px-8 rounded-xl hover:bg-primary-dark transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                    Enviar mensaje
                    <span class="material-symbols-outlined text-lg">send</span>
                </button>
            </form>
            </div>
        </div>
    </div>
</div>

@endsection
