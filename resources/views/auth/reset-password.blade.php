@extends('layouts.auth')

@section('title', 'Crear Contraseña')

@section('content')
<div class="w-full max-w-[400px] mx-auto bg-white shadow-2xl shadow-slate-200/50 overflow-hidden rounded-[1.75rem] border border-slate-100">

    <div class="px-7 pt-10 pb-8">

        {{-- Encabezado --}}
        <div class="text-center mb-10 px-2">
            <h1 class="text-slate-900 text-[1.35rem] font-extrabold tracking-tight leading-snug">
                Nueva Contraseña
            </h1>
            <p class="text-[0.95rem] font-medium text-slate-500 mt-2 tracking-wide">
                Protege tu cuenta de Stitch & Co
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="space-y-5">
                <div>
                    <label class="block text-[0.9rem] font-semibold text-slate-700 mb-2 ml-1">Nueva contraseña</label>
                    <div class="relative group">
                        <input name="password" type="password" id="pass1" required
                               class="block w-full px-4 py-[0.8rem] rounded-2xl border border-slate-200 focus:ring-1 focus:ring-[#8b5cf6] focus:border-[#8b5cf6] outline-none transition-all bg-white text-slate-800 text-[0.95rem] tracking-widest placeholder:tracking-normal placeholder:text-slate-300"
                               placeholder="••••••••">
                        <button type="button" onclick="togg('pass1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors outline-none cursor-pointer">
                            <span class="material-symbols-outlined text-[1.35rem]">visibility</span>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[0.9rem] font-semibold text-slate-700 mb-2 ml-1">Confirmar contraseña</label>
                    <div class="relative group">
                        <input name="password_confirmation" type="password" id="pass2" required
                               class="block w-full px-4 py-[0.8rem] rounded-2xl border border-slate-200 focus:ring-1 focus:ring-[#8b5cf6] focus:border-[#8b5cf6] outline-none transition-all bg-white text-slate-800 text-[0.95rem] tracking-widest placeholder:tracking-normal placeholder:text-slate-300"
                               placeholder="••••••••">
                        <button type="button" onclick="togg('pass2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors outline-none cursor-pointer">
                            <span class="material-symbols-outlined text-[1.35rem]">visibility</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit"
                        class="w-full bg-[#8b5cf6] hover:bg-[#7c3aed] text-white font-semibold py-[1.1rem] rounded-2xl
                               shadow-md shadow-[#8b5cf6]/25 hover:shadow-lg hover:shadow-[#8b5cf6]/40 hover:-translate-y-[1px]
                               transition-all duration-300 text-[1.05rem]">
                    Actualizar Clave
                </button>
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-[0.92rem] font-medium text-slate-400 hover:text-slate-500 transition-colors">
                        Cancelar y volver al inicio
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function togg(id) {
        const x = document.getElementById(id);
        const btn = x.nextElementSibling;
        const icon = btn.querySelector('.material-symbols-outlined');
        
        if (x.type === "password") {
            x.type = "text";
            icon.innerText = "visibility_off";
        } else {
            x.type = "password";
            icon.innerText = "visibility";
        }
    }
</script>
@endsection
