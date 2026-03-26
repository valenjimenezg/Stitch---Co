@extends('layouts.auth')

@section('title', 'Verificar código')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-primary/5 p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl">verified_user</span>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 mb-2">Verificar código</h1>
        <p class="text-slate-500 mb-8">Ingresa el código de 6 dígitos enviado por {{ session('recovery_method') === 'email' ? 'correo electrónico' : session('recovery_method') }}</p>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify') }}" class="space-y-6">
            @csrf
            <div>
                <input name="code" type="text" maxlength="6"
                       class="block w-full text-center text-3xl font-black tracking-[1em] px-4 py-4 rounded-xl border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-300"
                       placeholder="••••••" required autofocus/>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                Verificar y Continuar
            </button>
        </form>

        <div class="mt-8 text-center space-y-4">
            <p class="text-sm text-slate-500">¿No recibiste el código?</p>
            <form action="{{ route('password.send_code') }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="{{ session('recovery_method') }}">
                <button type="submit" class="text-sm font-semibold text-primary hover:underline">Reenviar código</button>
            </form>
            <a href="{{ route('password.selection') }}" class="block text-sm font-semibold text-slate-400 hover:text-slate-600">
                Elegir otro método
            </a>
        </div>
    </div>
</div>
@endsection
