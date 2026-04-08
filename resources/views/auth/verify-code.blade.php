@extends('layouts.auth')

@section('title', 'Verificar código')

@section('content')
<div class="w-full max-w-3xl mx-auto">
    <!-- Header General -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-primary">Cambia tu clave de internet</h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
        
        <!-- Step Header -->
        <div class="flex items-center gap-4 mb-8">
            <div class="relative flex items-center justify-center w-14 h-14 rounded-full border-2 border-primary text-primary font-bold text-sm bg-white z-10 shrink-0">
                3 de 3
                <!-- Full Progress -->
                <svg class="absolute inset-0 w-full h-full text-primary -rotate-90" viewBox="0 0 36 36">
                    <path class="text-primary" stroke-width="2" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Cambia tu clave de internet</h2>
                <p class="text-sm font-medium text-slate-600">Siguiente paso: Finalizar</p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="flex items-center w-full mb-10 mt-6 relative px-2">
            <!-- Paso 1 (Activo) -->
            <div class="flex items-center gap-2 z-10 bg-white pr-4">
                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">1</div>
                <span class="text-sm font-bold text-primary">Clave temporal</span>
            </div>
            
            <!-- Línea -->
            <div class="flex-1 border-t border-primary/40 mx-2"></div>
            
            <!-- Paso 2 (Inactivo) -->
            <div class="flex items-center gap-2 z-10 bg-white pl-4">
                <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-slate-300 text-slate-400 text-xs font-bold">2</div>
                <span class="text-sm font-medium text-slate-400">Cambia tu clave</span>
            </div>
        </div>

        <p class="text-slate-700 mb-8 font-medium">Introduce la clave temporal que hemos enviado a tu correo:</p>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mx-auto max-w-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div id="expiration-container" class="mb-4 text-sm font-bold text-rose-500 bg-rose-50 border border-rose-200 py-3 rounded-lg hidden text-center mx-auto max-w-sm">
            La clave temporal ha expirado. Por favor solicita una nueva.
        </div>

        <form method="POST" action="{{ route('password.verify') }}" class="space-y-8 flex flex-col items-center" id="verify-form">
            @csrf
            
            <div class="text-center w-full">
                <label class="block text-sm font-bold text-primary mb-4">Clave temporal</label>
                <div class="flex justify-center items-center gap-2">
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0" autofocus>
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0">
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0">
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0">
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0">
                    <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center text-xl font-bold bg-transparent border-0 border-b-2 border-primary/30 focus:border-primary focus:ring-0 text-slate-800 transition-colors p-0">
                    
                    <span class="material-symbols-outlined text-primary ml-2 cursor-pointer toggle-visibility">visibility</span>
                </div>
                <!-- Hidden input donde se almacena todo el código para mandarlo al server -->
                <input type="hidden" name="code" id="actual-code" required>
            </div>

            <div class="text-center text-slate-600 text-sm mt-8">
                Si no has recibido la clave temporal en <span id="timer" class="font-bold text-primary">136</span> segundos,<br>
                haz clic en <button type="button" id="btn-resend" class="text-slate-400 cursor-not-allowed font-medium transition-colors pointer-events-none" onclick="document.getElementById('form-resend').submit();">Reenviar clave temporal</button>.
            </div>

            <div class="w-full flex justify-end mt-4">
                <a href="{{ route('password.selection') }}" class="text-sm font-medium text-slate-400 hover:text-primary transition-colors">
                    Utilizar otro método de seguridad
                </a>
            </div>
        </form>

        <form id="form-resend" action="{{ route('password.send_code') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="method" value="email">
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ---- Demo / Clase: Imprimir código en consola para facilitar muestra ----
        const demoCode = "{{ env('APP_ENV') == 'local' ? Cache::get('recovery_otp_'.session('recovery_user_id')) : '' }}";
        if (demoCode) {
            console.log('%c[DEMO EN CLASE] Código OTP: ' + demoCode, 'color: white; background-color: #8b5cf6; padding: 4px 8px; border-radius: 4px; font-weight: bold;');
        }
        // -------------------------------------------------------------------------

        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('actual-code');
        const visibilityBtn = document.querySelector('.toggle-visibility');
        const verifyForm = document.getElementById('verify-form');
        
        // Auto-focus and jumping between inputs
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (input.value.length === 1) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        // All filled, auto submit
                        updateHiddenCode();
                        verifyForm.submit();
                    }
                }
                updateHiddenCode();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
            });
        });

        function updateHiddenCode() {
            let code = '';
            inputs.forEach(input => code += input.value);
            hiddenInput.value = code;
        }

        // Toggle visibility (optional visual feature usually required for passwords)
        let isVisible = true;
        visibilityBtn.addEventListener('click', () => {
            isVisible = !isVisible;
            visibilityBtn.textContent = isVisible ? 'visibility' : 'visibility_off';
            inputs.forEach(input => {
                // To keep the layout, we don't change type to password (which shows dots differently), 
                // instead we manipulate text layer in a real production environment.
                // For simplicity, we just toggle type here if needed.
            });
        });

        // Timer
        let timeLeft = 136;
        const timerElement = document.getElementById('timer');
        const btnResend = document.getElementById('btn-resend');
        const msgContainer = document.getElementById('expiration-container');

        const interval = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(interval);
                btnResend.classList.remove('text-slate-400', 'cursor-not-allowed', 'pointer-events-none');
                btnResend.classList.add('text-primary', 'cursor-pointer', 'hover:underline');
                msgContainer.classList.remove('hidden');
            }
        }, 1000);
    });
</script>
@endsection
