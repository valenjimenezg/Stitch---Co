@extends('layouts.auth')

@section('title', 'Nueva Contraseña')

@section('content')
<div class="w-full max-w-4xl mx-auto">
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
            <!-- Paso 1 (Inactivo, ya completado) -->
            <div class="flex items-center gap-2 z-10 bg-white pr-4">
                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">1</div>
                <span class="text-sm font-medium text-primary">Clave temporal</span>
            </div>
            
            <!-- Línea (Activa) -->
            <div class="flex-1 border-t border-primary mx-2"></div>
            
            <!-- Paso 2 (Activo) -->
            <div class="flex items-center gap-2 z-10 bg-white pl-4">
                <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-primary text-primary text-xs font-bold">2</div>
                <span class="text-sm font-bold text-slate-600">Cambia tu clave</span>
            </div>
        </div>

        <p class="text-slate-600 mb-8 font-medium">Recuerda no utilizar tu información personal (ejemplo: tu año de nacimiento).</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Columna Izquierda: Inputs -->
                <div class="w-full md:w-1/2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-primary mb-1">Clave de internet</label>
                        <div class="relative border-b-2 border-primary/20 focus-within:border-primary transition-colors pb-1">
                            <input name="password" type="password" id="pass1" required
                                   class="w-full bg-transparent border-none focus:ring-0 pl-0 pr-10 py-1 text-slate-800 tracking-widest placeholder:tracking-normal placeholder:text-slate-400 font-medium"
                                   placeholder="Ingresa...">
                            <button type="button" onclick="togg('pass1')" class="absolute right-0 top-1/2 -translate-y-1/2 text-primary hover:text-primary-dark transition-colors outline-none cursor-pointer">
                                <span class="material-symbols-outlined text-[20px] select-none">visibility_off</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-primary mb-1">Confirma tu clave de internet</label>
                        <div class="relative border-b-2 border-primary/20 focus-within:border-primary transition-colors pb-1">
                            <input name="password_confirmation" type="password" id="pass2" required
                                   class="w-full bg-transparent border-none focus:ring-0 pl-0 pr-10 py-1 text-slate-800 tracking-widest placeholder:tracking-normal placeholder:text-slate-400 font-medium"
                                   placeholder="Ingresa...">
                            <button type="button" onclick="togg('pass2')" class="absolute right-0 top-1/2 -translate-y-1/2 text-primary hover:text-primary-dark transition-colors outline-none cursor-pointer">
                                <span class="material-symbols-outlined text-[20px] select-none">visibility_off</span>
                            </button>
                        </div>
                        <p id="password-match-error" class="hidden text-xs text-rose-500 mt-2 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span> Las contraseñas no coinciden.
                        </p>
                    </div>
                    
                    <div class="pt-6">
                        <button type="submit" id="btn-submit"
                                class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] w-full md:w-auto">
                            Actualizar Clave
                        </button>
                    </div>
                </div>

                <!-- Columna Derecha: Reglas -->
                <div class="w-full md:w-1/2 bg-blue-50/50 rounded-xl p-6 border border-blue-100/50 self-start">
                    <p class="font-bold text-primary mb-4">¿Qué debe tener tu Clave de internet?</p>
                    <ul class="space-y-3 text-sm text-slate-600 font-medium">
                        <li id="req-length" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors">radio_button_unchecked</span>
                            Entre 8 y 15 caracteres.
                        </li>
                        <li id="req-case" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors">radio_button_unchecked</span>
                            Letras mayúsculas y minúsculas.
                        </li>
                        <li id="req-num" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors">radio_button_unchecked</span>
                            Números.
                        </li>
                        <li id="req-spec" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors">radio_button_unchecked</span>
                            Al menos uno de los siguientes caracteres especiales: <span class="font-bold">=, *, -, ., _</span>
                        </li>
                        <li id="req-no-rep" class="flex items-center gap-2 leading-tight">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors shrink-0 mt-0.5 self-start">radio_button_unchecked</span>
                            No más de 2 caracteres iguales consecutivos (alfabéticos o numéricos).
                        </li>
                        <li id="req-match" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-slate-300 indicator transition-colors">radio_button_unchecked</span>
                            Coincidir con la confirmación.
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                    Cancelar
                </a>
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
            icon.innerText = "visibility";
        } else {
            x.type = "password";
            icon.innerText = "visibility_off";
        }
    }

    function checkPasswordMatch() {
        const pwd = document.getElementById('pass1')?.value || '';
        const confirm = document.getElementById('pass2')?.value || '';
        const submitBtn = document.getElementById('btn-submit');
        const errorMsg = document.getElementById('password-match-error');

        const lengthValid = pwd.length >= 8 && pwd.length <= 15;
        const caseValid   = /[a-z]/.test(pwd) && /[A-Z]/.test(pwd);
        const numValid    = /[0-9]/.test(pwd);
        const specValid   = /[=\*\-\._]/.test(pwd) && /^[a-zA-Z0-9=\*\-\._]+$/.test(pwd);
        
        const lowerPwd = pwd.toLowerCase();
        const sequences = [
            '012', '123', '234', '345', '456', '567', '678', '789',
            '987', '876', '765', '654', '543', '432', '321', '210',
            'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl', 'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv', 'uvw', 'vwx', 'wxy', 'xyz',
            'zyx', 'yxw', 'xwv', 'wvu', 'vut', 'uts', 'tsr', 'srq', 'rqp', 'qpo', 'pon', 'onm', 'nml', 'mlk', 'lkj', 'kji', 'jih', 'ihg', 'hgf', 'gfe', 'fed', 'edc', 'dcb', 'cba'
        ];
        const hasSequences = sequences.some(seq => lowerPwd.includes(seq));
        const repValid    = !/(.)\1\1/.test(pwd) && pwd.length > 0 && !hasSequences;
        const spaceValid  = pwd.length > 0 && !/\s/.test(pwd);
        const isMatch     = pwd !== '' && pwd === confirm;

        const setIndicatorStatus = (elId, isValid) => {
            const el = document.querySelector(`#${elId} .indicator`);
            if (!el) return;
            if (isValid) {
                el.textContent = 'check_circle';
                el.classList.remove('text-slate-300');
                el.classList.add('text-emerald-500');
            } else {
                el.textContent = 'radio_button_unchecked';
                el.classList.remove('text-emerald-500');
                el.classList.add('text-slate-300');
            }
        };

        setIndicatorStatus('req-length', lengthValid);
        setIndicatorStatus('req-case', caseValid);
        setIndicatorStatus('req-num', numValid);
        setIndicatorStatus('req-spec', specValid);
        setIndicatorStatus('req-no-rep', repValid);
        setIndicatorStatus('req-match', isMatch);

        if (confirm === '') {
            errorMsg?.classList.add('hidden');
        } else if (!isMatch) {
            errorMsg?.classList.remove('hidden');
        } else {
            errorMsg?.classList.add('hidden');
        }

        const allValid = lengthValid && caseValid && numValid && specValid && repValid && spaceValid && isMatch;

        if (allValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    const validateInputs = document.querySelectorAll('input[type="password"]');
    validateInputs.forEach(input => {
        const errorEl = document.createElement('p');
        errorEl.className = 'hidden text-xs text-rose-500 mt-1.5 font-bold items-center gap-1';
        errorEl.innerHTML = '<span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span>';
        
        if (input.parentNode.classList.contains('relative')) {
            input.parentNode.parentNode.appendChild(errorEl);
        } else {
            input.parentNode.appendChild(errorEl);
        }

        const checkErrors = function() {
            const val = input.value;
            let errorMessage = '';

            if (val.length === 0) {
                errorEl.classList.add('hidden');
                errorEl.classList.remove('flex');
                checkPasswordMatch();
                return;
            }

            if (/\s/.test(val)) {
                errorMessage = 'No se permiten espacios en este campo.';
            }

            if (errorMessage) {
                errorEl.querySelector('.error-text').textContent = errorMessage;
                errorEl.classList.remove('hidden');
                errorEl.classList.add('flex');
                
                const submitBtn = document.getElementById('btn-submit');
                if(submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                errorEl.classList.add('hidden');
                errorEl.classList.remove('flex');
                checkPasswordMatch();
            }
        };

        input.addEventListener('input', checkErrors);
        input.addEventListener('blur', checkErrors);
    });

    document.getElementById('pass1')?.addEventListener('input', checkPasswordMatch);
    document.getElementById('pass2')?.addEventListener('input', checkPasswordMatch);
    
    document.addEventListener('DOMContentLoaded', checkPasswordMatch);
</script>
@endsection
