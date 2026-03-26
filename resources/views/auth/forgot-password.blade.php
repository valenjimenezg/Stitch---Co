@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-primary/5 p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Recuperar Contraseña</h1>
            <p class="text-slate-500 mt-2">Te enviaremos un enlace para restablecer tu cuenta</p>
        </div>

        @if (session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.identify') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                <input name="email" type="email" value="{{ old('email') }}"
                       class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                       placeholder="ejemplo@correo.com" required autofocus/>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                Enviar Enlace de Recuperación
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-primary hover:underline flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>
@endsection
