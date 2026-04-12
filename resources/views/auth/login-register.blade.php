@extends('layouts.auth')

@section('title', 'Acceso')

@section('content')
<div class="w-full max-w-md mx-auto transition-all duration-500">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-primary/5">

        {{-- Tabs --}}
        <div class="flex border-b border-primary/10">
            <button id="tab-login" onclick="switchTab('login')"
                    class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors">
                Iniciar Sesión
            </button>
            <button id="tab-registro" onclick="switchTab('registro')"
                    class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors">
                Registrarse
            </button>
        </div>

        <div class="p-8">

            {{-- Flash Information Message --}}
            @if(session('info'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm">
                    <span class="material-symbols-outlined mt-0.5 text-blue-500">info</span>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
            @endif

            {{-- ===== FORM LOGIN ===== --}}
            <div id="panel-login">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">¡Bienvenida de nuevo!</h1>
                    <p class="text-slate-500 mt-2">Accede a tu cuenta de Stitch &amp; Co</p>
                </div>

                @if($errors->any() && old('_form') === 'login')
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_form" value="login">
                    <input type="hidden" name="_tab" value="login">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="ejemplo@correo.com" required/>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                            <a class="text-xs font-semibold text-primary hover:underline" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="relative">
                            <input name="password" id="login-password" type="password"
                                   class="block w-full px-4 pr-12 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="••••••••" required/>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'login-password')">
                                <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input name="remember" type="checkbox" id="remember"
                               class="w-4 h-4 rounded border-primary/30 text-primary focus:ring-primary"/>
                        <label class="ml-2 text-sm text-slate-600" for="remember">Mantener sesión iniciada</label>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                        Entrar
                    </button>
                </form>
            </div>

            {{-- ===== FORM REGISTRO ===== --}}
            <div id="panel-registro">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Crea tu cuenta</h1>
                    <p class="text-slate-500 mt-2">Únete a la comunidad Stitch &amp; Co</p>
                </div>

                @if($errors->any() && old('_form') === 'registro')
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="registro">
                    <input type="hidden" name="_tab" value="registro">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                            <input name="nombre" type="text" value="{{ old('nombre') }}"
                                   class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="María" required/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Apellido</label>
                            <input name="apellido" type="text" value="{{ old('apellido') }}"
                                   class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="González" required/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Documento de Identidad <span class="text-rose-500">*</span></label>
                        <div class="flex">
                            <select name="tipo_documento" id="document_type" class="block w-24 px-3 py-3 rounded-l-lg border border-r-0 border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-slate-700">
                                <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                                <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                                <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                                <option value="G" {{ old('tipo_documento') == 'G' ? 'selected' : '' }}>G</option>
                            </select>
                            <input name="documento_identidad" id="document_number" type="text" value="{{ old('documento_identidad') }}"
                                   class="block w-full px-4 py-3 rounded-r-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="12345678" required/>
                        </div>
                        <p id="document-feedback" class="hidden text-xs text-rose-500 mt-1.5 font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> Este documento ya se encuentra registrado.</p>

                        <div class="mt-3 bg-indigo-50 text-indigo-800 px-3 py-2 border border-indigo-200 rounded-lg flex items-start gap-2 shadow-sm text-left">
                            <span class="material-symbols-outlined mt-0.5 text-[18px] text-indigo-500">info</span>
                            <div>
                                <p class="text-xs font-bold mb-0.5">¡Casi listo!</p>
                                <p class="text-[11px] leading-snug font-medium">Para procesar tus pedidos y emitir tu factura legal, es <strong class="font-bold uppercase">obligatorio</strong> que ingreses tu identificación. Puedes usar tu Cédula, Extranjería o RIF.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="ejemplo@correo.com" required/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono (opcional)</label>
                        <div class="flex">
                            <select name="telefono_prefijo" class="block w-24 px-2 py-3 rounded-l-lg border border-r-0 border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-slate-700 text-sm">
                                <option value="0412" {{ old('telefono_prefijo') == '0412' ? 'selected' : '' }}>0412</option>
                                <option value="0414" {{ old('telefono_prefijo') == '0414' ? 'selected' : '' }}>0414</option>
                                <option value="0424" {{ old('telefono_prefijo') == '0424' ? 'selected' : '' }}>0424</option>
                                <option value="0416" {{ old('telefono_prefijo') == '0416' ? 'selected' : '' }}>0416</option>
                                <option value="0426" {{ old('telefono_prefijo') == '0426' ? 'selected' : '' }}>0426</option>
                                <option value="0212" {{ old('telefono_prefijo') == '0212' ? 'selected' : '' }}>0212</option>
                            </select>
                            <input name="telefono_numero" type="text" value="{{ old('telefono_numero') }}"
                                   class="block w-full px-4 py-3 rounded-r-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="1234567" maxlength="7"/>
                        </div>
                        <p id="phone-feedback" class="hidden text-xs text-rose-500 mt-1.5 font-bold items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <input name="password" id="reg-password" type="password"
                                   class="block w-full px-4 pr-12 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="Contraseña robusta" required/>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'reg-password')">
                                <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                        <div class="relative">
                            <input name="password_confirmation" id="reg-password-confirm" type="password"
                                   class="block w-full px-4 pr-12 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="Repite tu contraseña" required/>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'reg-password-confirm')">
                                <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                            </button>
                        </div>
                        
                        <!-- Checklist Box Antigravity Style -->
                        <div class="mt-3 bg-slate-50 border border-slate-200 rounded-xl p-5 text-[13px] font-medium text-slate-600 shadow-sm">
                            <p class="mb-3 font-bold text-slate-800 border-b border-slate-200 pb-2 uppercase tracking-wide">Requisitos de Contraseña</p>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                <li id="req-length" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight">Tener entre <strong>8 y 15</strong> caracteres.</span></li>
                                <li id="req-case" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight">Incluir una <strong>Mayúscula (A-Z)</strong> y una <strong>minúscula (a-z)</strong>.</span></li>
                                <li id="req-num" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight">Incluir al menos un <strong>número (0-9)</strong>.</span></li>
                                <li id="req-spec" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight">Contener un símbolo válido: <strong>* . - _ @ #</strong> (sin signos raros ni ñ/acentos).</span></li>
                                <li id="req-no-rep" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight"><strong>No</strong> tener secuencias obvias de letras o números (ej. abc, 123).</span></li>
                                <li id="req-identity" class="flex items-start gap-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight"><strong>No</strong> contener tu nombre, apellido, cédula o fecha de nacimiento.</span></li>
                                <li id="req-space" class="flex items-start gap-2 col-span-1 md:col-span-2 pt-2 border-t border-slate-200 mt-1"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight"><strong>No</strong> contener absolutamente ningún espacio en blanco.</span></li>
                                <li id="req-match" class="flex items-start gap-2 col-span-1 md:col-span-2"><span class="material-symbols-outlined text-[18px] text-slate-300 transition-colors mt-0.5">radio_button_unchecked</span> <span class="leading-tight">Ambas contraseñas introducidas <strong>coinciden</strong>.</span></li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit" id="btn-registro"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                        Crear Cuenta
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ACTIVE   = ['border-primary', 'text-primary', 'bg-primary/5'];
    const INACTIVE = ['border-transparent', 'text-slate-400', 'hover:text-slate-600'];

    function switchTab(tab) {
        ['login', 'registro'].forEach(t => {
            const btn   = document.getElementById('tab-' + t);
            const panel = document.getElementById('panel-' + t);
            if (t === tab) {
                btn.classList.add(...ACTIVE);
                btn.classList.remove(...INACTIVE);
                panel.style.display = 'block';
            } else {
                btn.classList.remove(...ACTIVE);
                btn.classList.add(...INACTIVE);
                panel.style.display = 'none';
            }
        });
    }

    // Inicializar con el tab correcto (login por defecto, o registro si hubo error en ese form)
    switchTab('{{ old('_tab', 'login') }}');

    // Funciones para UI de Contraseñas
    function togglePassword(btn, inputId) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('span');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function checkPasswordMatch() {
        const pwd = document.getElementById('reg-password')?.value || '';
        const confirm = document.getElementById('reg-password-confirm')?.value || '';
        const submitBtn = document.getElementById('btn-registro');

        // Reglas de validación
        const lowerPwd    = pwd.toLowerCase();
        const lengthValid = pwd.length >= 8 && pwd.length <= 15;
        const caseValid   = /[a-z]/.test(pwd) && /[A-Z]/.test(pwd);
        const numValid    = /[0-9]/.test(pwd);
        // Exige un carácter especial válido Y restringe a que TODOS los caracteres sean letras puras o los válidos.
        const specValid   = /[*.\-_@#]/.test(pwd) && /^[a-zA-Z0-9*.\-_@#]+$/.test(pwd);
        
        // Secuencias
        const sequences = [
            '012', '123', '234', '345', '456', '567', '678', '789',
            '987', '876', '765', '654', '543', '432', '321', '210',
            'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl', 'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv', 'uvw', 'vwx', 'wxy', 'xyz',
            'zyx', 'yxw', 'xwv', 'wvu', 'vut', 'uts', 'tsr', 'srq', 'rqp', 'qpo', 'pon', 'onm', 'nml', 'mlk', 'lkj', 'kji', 'jih', 'ihg', 'hgf', 'gfe', 'fed', 'edc', 'dcb', 'cba'
        ];
        const hasSequences = sequences.some(seq => lowerPwd.includes(seq));
        const repValid    = pwd.length > 0 && !hasSequences;
        const spaceValid  = pwd.length > 0 && !/\s/.test(pwd);

        // Identidad
        const nom = document.querySelector('input[name="nombre"]')?.value.trim().toLowerCase() || '';
        const ape = document.querySelector('input[name="apellido"]')?.value.trim().toLowerCase() || '';
        const doc = document.querySelector('input[name="documento_identidad"]')?.value.trim() || '';
        // Si hay un campo de nacimiento 'ano_nacimiento' se atrapará aquí pero como no hay, saltará.
        const anio = document.querySelector('input[name="ano_nacimiento"]')?.value.trim() || '';

        let hasIdentity = false;
        if (nom && lowerPwd.includes(nom)) hasIdentity = true;
        if (ape && lowerPwd.includes(ape)) hasIdentity = true;
        if (doc && doc.length > 4 && lowerPwd.includes(doc)) hasIdentity = true;
        if (anio && anio.length === 4 && lowerPwd.includes(anio)) hasIdentity = true;

        const idValid = pwd.length > 0 && !hasIdentity;

        // Formato para íconos
        const setIconStatus = (elId, isValid) => {
            const el = document.querySelector(`#${elId} span`);
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

        setIconStatus('req-length', lengthValid);
        setIconStatus('req-case', caseValid);
        setIconStatus('req-num', numValid);
        setIconStatus('req-spec', specValid);
        setIconStatus('req-no-rep', repValid);
        setIconStatus('req-space', spaceValid);
        setIconStatus('req-identity', idValid);

        // Validar confirmación
        const isMatch = pwd !== '' && pwd === confirm;
        setIconStatus('req-match', isMatch);

        const isNotDuplicated = !document.getElementById('document-feedback')?.classList.contains('!flex');
        const isValidLength = !document.querySelector('#document_number')?.parentNode.parentNode.querySelector('.text-rose-500:not(#document-feedback)')?.classList.contains('!flex') && !document.querySelector('#document_number')?.parentNode.parentNode.querySelector('p:not(#document-feedback)')?.classList.contains('!flex'); 
        // We added validationErrorEl which isn't #document-feedback. We can just rely on validationErrorEl inner logic. Actually let's just do:
        const hasLocalError = document.querySelector('#document_number')?.parentNode.parentNode.querySelector('p:not(#document-feedback)')?.classList.contains('!flex') || !document.querySelector('#document_number')?.parentNode.parentNode.querySelector('p:not(#document-feedback)')?.classList.contains('hidden');

        // Let's refine checking. We need to check if ANY error message we dynamically created is currently visible.
        const allLocalErrorEls = Array.from(document.querySelectorAll('p:has(>.error-text)'));
        const areAllLocalValid = allLocalErrorEls.every(p => p.classList.contains('hidden'));

        const allValid = lengthValid && caseValid && numValid && specValid && repValid && spaceValid && idValid && isMatch && isNotDuplicated && areAllLocalValid;

        if (allValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    document.getElementById('reg-password')?.addEventListener('input', checkPasswordMatch);
    document.getElementById('reg-password-confirm')?.addEventListener('input', checkPasswordMatch);
    
    // AJAX validación de Documento en tiempo real
    const docTypeEl = document.getElementById('document_type');
    const docNumEl = document.getElementById('document_number');
    const feedbackEl = document.getElementById('document-feedback');
    const validationErrorEl = document.createElement('p');
    validationErrorEl.className = 'hidden text-xs text-rose-500 mt-1.5 font-bold flex items-center gap-1';
    validationErrorEl.innerHTML = '<span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span>';
    docNumEl?.parentNode.parentNode.appendChild(validationErrorEl);

    let docTimeout = null;

    docNumEl?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        checkDocumentExists();
    });

    function checkDocumentExists() {
        clearTimeout(docTimeout);
        docTimeout = setTimeout(() => {
            const type = docTypeEl.value;
            const number = docNumEl.value.trim();
            if(!number) {
                feedbackEl.classList.remove('!flex');
                validationErrorEl.classList.add('hidden');
                checkPasswordMatch();
                return;
            }

            let localError = '';
            if (/^(\d)\1+$/.test(number)) {
                localError = 'Secuencia numérica repetida inválida.';
            } else if ((type === 'V' || type === 'E') && (number.length < 6 || number.length > 9)) {
                localError = 'El documento debe tener entre 6 y 9 números.';
            } else if ((type === 'J' || type === 'G') && (number.length < 6 || number.length > 9)) {
                localError = 'El RIF debe tener entre 6 y 9 números.';
            }

            if(localError) {
                validationErrorEl.querySelector('.error-text').textContent = localError;
                validationErrorEl.classList.remove('hidden');
                feedbackEl.classList.remove('!flex');
                feedbackEl.classList.add('hidden');
                checkPasswordMatch();
                return;
            } else {
                validationErrorEl.classList.add('hidden');
            }

            fetch('{{ route('api.check-document') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tipo_documento: type, documento_identidad: number })
            })
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    feedbackEl.classList.remove('hidden');
                    feedbackEl.classList.add('!flex');
                } else {
                    feedbackEl.classList.remove('!flex');
                    feedbackEl.classList.add('hidden');
                }
                checkPasswordMatch(); // re-evaluamos botón submit
            }).catch(e => console.error(e));
        }, 500); // Debounce de 500ms
    }

    docTypeEl?.addEventListener('change', checkDocumentExists);
    docNumEl?.addEventListener('blur', checkDocumentExists);

    // Restringir el número de teléfono exclusivamente a caracteres numéricos y verificar longitud/repetición
    const phoneNumEl = document.querySelector('input[name="telefono_numero"]');
    const phoneFeedbackEl = document.getElementById('phone-feedback');
    phoneNumEl?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        const val = this.value;
        let localError = '';
        if (val.length > 0 && val.length < 7) {
            localError = 'El número debe tener 7 dígitos completos.';
        } else if (/^(\d)\1+$/.test(val) && val.length > 0) {
            localError = 'Secuencia numérica repetida inválida.';
        }

        if (localError && val.length > 0) {
            phoneFeedbackEl.querySelector('.error-text').textContent = localError;
            phoneFeedbackEl.classList.remove('hidden');
            phoneFeedbackEl.classList.add('flex');
            checkPasswordMatch(); // update global validation
        } else {
            phoneFeedbackEl.classList.add('hidden');
            phoneFeedbackEl.classList.remove('flex');
            checkPasswordMatch();
        }
    });

    phoneNumEl?.addEventListener('blur', function(e) {
        const val = this.value;
        if(val.length === 0) {
            phoneFeedbackEl.classList.add('hidden');
            phoneFeedbackEl.classList.remove('flex');
            checkPasswordMatch();
        }
    });

    // Validación proactiva de correos (en tiempo real en vez de blur)reos y Contraseñas)
    const validateInputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    
    validateInputs.forEach(input => {
        // Crear contenedor del mensaje de error (estilo Mercantil) si no existe
        const errorEl = document.createElement('p');
        errorEl.className = 'hidden text-xs text-rose-500 mt-1.5 font-bold items-center gap-1';
        errorEl.innerHTML = '<span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span>';
        
        // Envolver o insertar adecuadamente (para contraseñas considerar el relative que cubre el ícono visual)
        if (input.type === 'password' && input.parentNode.classList.contains('relative')) {
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
                if(input.id === 'reg-password' || input.id === 'reg-password-confirm') checkPasswordMatch();
                return;
            }

            // 1. Prohibir visualmente los espacios siempre
            if (/\s/.test(val)) {
                errorMessage = 'No se permiten espacios en este campo.';
            } 
            // 2. Formato de correos estricto (solo si no hay espacios, para no sobreescribir el msg)
            else if (input.type === 'email' && (/@\.@/.test(val) || !/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(val))) {
                errorMessage = 'Formato de correo inválido (ej. usuario@dominio.com)';
            }

            if (errorMessage) {
                errorEl.querySelector('.error-text').textContent = errorMessage;
                errorEl.classList.remove('hidden');
                errorEl.classList.add('flex');
                
                // Si es la contraseña de registro pero tiene espacios, deshabilitamos el form
                if(input.id === 'reg-password' || input.id === 'reg-password-confirm') {
                    const submitBtn = document.getElementById('btn-registro');
                    if(submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            } else {
                errorEl.classList.add('hidden');
                errorEl.classList.remove('flex');
                if(input.id === 'reg-password' || input.id === 'reg-password-confirm') checkPasswordMatch();
            }
        };

        // Escuchar el evento input (a medida que escribe) "a medida que lo va intentando escribir le salga el mensaje"
        input.addEventListener('input', checkErrors);
        // Escuchar el blur para re-verificar por si acaso
        input.addEventListener('blur', checkErrors);
    });

    // Call initially to set button disabled state
    checkPasswordMatch();
</script>
@endpush
