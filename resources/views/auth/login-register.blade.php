@extends('layouts.auth')

@section('title', 'Acceso')

@section('content')

{{-- Flash Information Message --}}
@if(session('info'))
    <div class="mb-6 bg-violet-50 border border-violet-200 text-violet-700 px-4 py-3 rounded-2xl flex items-start gap-3">
        <span class="material-symbols-outlined mt-0.5 text-violet-500 text-[20px]">info</span>
        <p class="text-sm font-semibold">{{ session('info') }}</p>
    </div>
@endif

{{-- ===== FORM LOGIN ===== --}}
<div id="panel-login">
    <div class="mb-8">
        <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">¡Bienvenida de nuevo!</h1>
        <p style="color:#6b7280; font-size:14px;">Accede a tu cuenta de Stitch &amp; Co</p>
    </div>

    @if($errors->any() && old('_form') === 'login')
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">error</span>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="_form" value="login">
        <input type="hidden" name="_tab" value="login">

        <div>
            <label class="auth-label">Correo Electrónico</label>
            <input name="email" type="email" value="{{ old('email') }}"
                   class="auth-input"
                   placeholder="ejemplo@correo.com" required/>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="auth-label" style="margin-bottom:0;">Contraseña</label>
                <a style="font-size:12px; font-weight:600; color:#7c3aed;" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="relative">
                <input name="password" id="login-password" type="password"
                       class="auth-input pr-12"
                       placeholder="••••••••" required/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'login-password')">
                    <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input name="remember" type="checkbox" id="remember"
                   style="width:16px; height:16px; accent-color:#7c3aed; cursor:pointer;"/>
            <label style="font-size:13px; color:#6b7280; cursor:pointer;" for="remember">Mantener sesión iniciada</label>
        </div>

        <button type="submit" class="auth-btn">Entrar</button>
    </form>
</div>

{{-- ===== FORM REGISTRO ===== --}}
<div id="panel-registro">
    <div class="mb-6">
        <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">Crea tu cuenta</h1>
        <p style="color:#6b7280; font-size:14px;">Únete a la comunidad Stitch &amp; Co</p>
    </div>

    @if($errors->any() && old('_form') === 'registro')
        <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
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

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="auth-label">Nombre</label>
                <input name="nombre" type="text" value="{{ old('nombre') }}" class="auth-input" placeholder="María" required/>
            </div>
            <div>
                <label class="auth-label">Apellido</label>
                <input name="apellido" type="text" value="{{ old('apellido') }}" class="auth-input" placeholder="González" required/>
            </div>
        </div>

        <div>
            <label class="auth-label">Documento de Identidad <span class="text-rose-500">*</span></label>
            <div class="flex">
                <select name="tipo_documento" id="document_type" class="auth-input rounded-r-none border-r-0" style="width:72px; flex-shrink:0;">
                    <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                    <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                    <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                    <option value="G" {{ old('tipo_documento') == 'G' ? 'selected' : '' }}>G</option>
                </select>
                <input name="documento_identidad" id="document_number" type="text" value="{{ old('documento_identidad') }}"
                       class="auth-input rounded-l-none flex-1 border-l-0" placeholder="12345678" required/>
            </div>
            <p id="document-feedback" class="hidden text-xs text-rose-500 mt-1.5 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">error</span> Este documento ya se encuentra registrado.
            </p>
            <div class="mt-2.5 bg-violet-50 text-violet-800 px-3 py-2 border border-violet-200 rounded-xl flex items-start gap-2">
                <span class="material-symbols-outlined mt-0.5 text-[16px] text-violet-500">info</span>
                <div>
                    <p class="text-[11px] font-bold mb-0.5">¡Casi listo!</p>
                    <p class="text-[11px] leading-snug">Para procesar tus pedidos y emitir tu factura legal, necesitamos tu identificación. Puedes usar tu Cédula, Extranjería o RIF.</p>
                </div>
            </div>
        </div>

        <div>
            <label class="auth-label">Correo Electrónico</label>
            <input name="email" type="email" value="{{ old('email') }}" class="auth-input" placeholder="ejemplo@correo.com" required/>
        </div>

        <div>
            <label class="auth-label">Teléfono <span style="color:#9ca3af; font-weight:400;">(opcional)</span></label>
            <div class="flex">
                <select name="telefono_prefijo" class="auth-input rounded-r-none border-r-0 text-sm" style="width:80px; flex-shrink:0;">
                    <option value="0412" {{ old('telefono_prefijo') == '0412' ? 'selected' : '' }}>0412</option>
                    <option value="0414" {{ old('telefono_prefijo') == '0414' ? 'selected' : '' }}>0414</option>
                    <option value="0424" {{ old('telefono_prefijo') == '0424' ? 'selected' : '' }}>0424</option>
                    <option value="0416" {{ old('telefono_prefijo') == '0416' ? 'selected' : '' }}>0416</option>
                    <option value="0426" {{ old('telefono_prefijo') == '0426' ? 'selected' : '' }}>0426</option>
                    <option value="0212" {{ old('telefono_prefijo') == '0212' ? 'selected' : '' }}>0212</option>
                </select>
                <input name="telefono_numero" type="text" value="{{ old('telefono_numero') }}"
                       class="auth-input rounded-l-none flex-1 border-l-0" placeholder="1234567" maxlength="7"/>
            </div>
            <p id="phone-feedback" class="hidden text-xs text-rose-500 mt-1.5 font-bold items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span>
            </p>
        </div>

        <div>
            <label class="auth-label">Contraseña</label>
            <div class="relative">
                <input name="password" id="reg-password" type="password"
                       class="auth-input pr-12" placeholder="Contraseña robusta" required/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'reg-password')">
                    <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                </button>
            </div>
        </div>

        <div>
            <label class="auth-label">Confirmar Contraseña</label>
            <div class="relative">
                <input name="password_confirmation" id="reg-password-confirm" type="password"
                       class="auth-input pr-12" placeholder="Repite tu contraseña" required/>
                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="togglePassword(this, 'reg-password-confirm')">
                    <span class="material-symbols-outlined text-[20px] select-none">visibility</span>
                </button>
            </div>
        </div>

        {{-- ── Indicador de Fortaleza estilo Google/Mercantil ── --}}
        <div id="pwd-strength-panel" style="display:none; background:#fafafa; border:1.5px solid #ede9fe; border-radius:18px; padding:16px 18px; margin-top:2px;">

            {{-- Barra de fortaleza --}}
            <div style="margin-bottom:14px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.07em;">Fortaleza</span>
                    <span id="pwd-strength-label" style="font-size:11px; font-weight:800; color:#9ca3af;">—</span>
                </div>
                <div style="display:flex; gap:4px;">
                    <div id="bar1" style="height:5px; flex:1; border-radius:99px; background:#e5e7eb; transition:background .3s;"></div>
                    <div id="bar2" style="height:5px; flex:1; border-radius:99px; background:#e5e7eb; transition:background .3s;"></div>
                    <div id="bar3" style="height:5px; flex:1; border-radius:99px; background:#e5e7eb; transition:background .3s;"></div>
                    <div id="bar4" style="height:5px; flex:1; border-radius:99px; background:#e5e7eb; transition:background .3s;"></div>
                </div>
            </div>

            {{-- Requisitos como checklist --}}
            <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; margin-bottom:10px;">Requisitos</p>
            <ul style="display:grid; grid-template-columns:1fr 1fr; gap:7px 12px;">
                <li id="req-length"  class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Entre <strong>8 y 15</strong> caracteres</span></li>
                <li id="req-case"    class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Mayúscula y minúscula</span></li>
                <li id="req-num"     class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Al menos un número</span></li>
                <li id="req-spec"    class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Un símbolo <strong>*.-_@#</strong></span></li>
                <li id="req-no-rep"  class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Sin secuencias (abc, 123)</span></li>
                <li id="req-identity" class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Sin nombre ni cédula</span></li>
                <li id="req-space"   class="pwd-req" style="grid-column:span 2;"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Sin espacios en blanco</span></li>
                <li id="req-match"   class="pwd-req" style="grid-column:span 2; border-top:1px solid #f0ebff; padding-top:7px; margin-top:2px;"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Ambas contraseñas <strong>coinciden</strong></span></li>
            </ul>
        </div>

        <button type="submit" id="btn-registro" class="auth-btn">Crear Cuenta</button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // ── Tabs ─────────────────────────────────────────────────────────
    function switchTab(tab) {
        ['login', 'registro'].forEach(t => {
            const btn   = document.getElementById('tab-' + t);
            const panel = document.getElementById('panel-' + t);
            if (t === tab) {
                btn.classList.add('active');
                panel.style.display = 'block';
            } else {
                btn.classList.remove('active');
                panel.style.display = 'none';
            }
        });
    }
    switchTab('{{ old('_tab', 'login') }}');

    // ── Toggle password visibility ────────────────────────────────────
    function togglePassword(btn, inputId) {
        const input = document.getElementById(inputId);
        const icon  = btn.querySelector('span');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    /* ── Estilos checklist contraseña ── */
    document.head.insertAdjacentHTML('beforeend', `
    <style>
    .pwd-req {
        display: flex; align-items: flex-start; gap: 6px;
        font-size: 12px; font-weight: 500; color: #6b7280;
        transition: color .2s;
    }
    .pwd-req .req-icon {
        font-size: 15px; color: #d1d5db;
        flex-shrink: 0; margin-top: 1px;
        transition: color .2s;
        font-variation-settings: 'FILL' 0;
    }
    .pwd-req.valid { color: #059669; }
    .pwd-req.valid .req-icon { color: #10b981; font-variation-settings: 'FILL' 1; }
    .pwd-req.invalid { color: #ef4444; }
    .pwd-req.invalid .req-icon { color: #ef4444; }
    </style>
    `);

    // ── Validación de contraseña ──────────────────────────────────────
    function checkPasswordMatch() {
        const pwd    = document.getElementById('reg-password')?.value || '';
        const confirm= document.getElementById('reg-password-confirm')?.value || '';
        const submitBtn = document.getElementById('btn-registro');

        const lowerPwd    = pwd.toLowerCase();
        const lengthValid = pwd.length >= 8 && pwd.length <= 15;
        const caseValid   = /[a-z]/.test(pwd) && /[A-Z]/.test(pwd);
        const numValid    = /[0-9]/.test(pwd);
        const specValid   = /[*.\/\-_@#!$%&?+]/.test(pwd);

        const sequences = [
            '012','123','234','345','456','567','678','789',
            '987','876','765','654','543','432','321','210',
            'abc','bcd','cde','def','efg','fgh','ghi','hij','ijk','jkl','klm','lmn','mno','nop','opq','pqr','qrs','rst','stu','tuv','uvw','vwx','wxy','xyz',
            'zyx','yxw','xwv','wvu','vut','uts','tsr','srq','rqp','qpo','pon','onm','nml','mlk','lkj','kji','jih','ihg','hgf','gfe','fed','edc','dcb','cba'
        ];
        const hasSequences = sequences.some(seq => lowerPwd.includes(seq));
        const repValid   = pwd.length > 0 && !hasSequences;
        const spaceValid = pwd.length > 0 && !/\s/.test(pwd);

        const nom = document.querySelector('input[name="nombre"]')?.value.trim().toLowerCase() || '';
        const ape = document.querySelector('input[name="apellido"]')?.value.trim().toLowerCase() || '';
        const doc = document.querySelector('input[name="documento_identidad"]')?.value.trim() || '';
        const anio= document.querySelector('input[name="ano_nacimiento"]')?.value.trim() || '';

        let hasIdentity = false;
        if (nom && lowerPwd.includes(nom)) hasIdentity = true;
        if (ape && lowerPwd.includes(ape)) hasIdentity = true;
        if (doc && doc.length > 4 && lowerPwd.includes(doc)) hasIdentity = true;
        if (anio && anio.length === 4 && lowerPwd.includes(anio)) hasIdentity = true;

        const idValid    = pwd.length > 0 && !hasIdentity;
        const isMatch    = pwd !== '' && pwd === confirm;

        const setReq = (elId, isValid, typed) => {
            const el = document.getElementById(elId);
            if (!el) return;
            const icon = el.querySelector('.req-icon') || el.querySelector('span:first-child');
            if (icon) {
                icon.textContent = isValid ? 'check_circle' : (typed ? 'cancel' : 'radio_button_unchecked');
            }
            el.classList.toggle('valid', isValid);
            el.classList.toggle('invalid', !isValid && typed);
            // fallback for old icon style
            if (!el.classList.contains('pwd-req')) {
                if (icon) {
                    icon.textContent = isValid ? 'check_circle' : 'radio_button_unchecked';
                    icon.classList.toggle('text-emerald-500', isValid);
                    icon.classList.toggle('text-slate-300', !isValid);
                }
            }
        };
        const typed = pwd.length > 0;
        setReq('req-length', lengthValid, typed);
        setReq('req-case', caseValid, typed);
        setReq('req-num', numValid, typed);
        setReq('req-spec', specValid, typed);
        setReq('req-no-rep', repValid, typed);
        setReq('req-space', spaceValid, typed);
        setReq('req-identity', idValid, typed);
        setReq('req-match', isMatch, confirm.length > 0);

        // ── Barra de fortaleza ────────────────────────────────────────
        const panel = document.getElementById('pwd-strength-panel');
        if (panel) {
            panel.style.display = typed ? 'block' : 'none';
            const score = [lengthValid, caseValid, numValid, specValid, repValid].filter(Boolean).length;
            const bars = ['bar1','bar2','bar3','bar4'];
            const lbl  = document.getElementById('pwd-strength-label');
            const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
            const labels = ['Muy débil','Débil','Regular','Fuerte'];
            bars.forEach((id, i) => {
                const bar = document.getElementById(id);
                if (bar) bar.style.background = i < score ? colors[Math.min(score-1,3)] : '#e5e7eb';
            });
            if (lbl) {
                lbl.textContent = typed ? labels[Math.min(score > 0 ? score-1 : 0, 3)] : '—';
                lbl.style.color = typed ? colors[Math.min(score > 0 ? score-1 : 0, 3)] : '#9ca3af';
            }
        }

        const isNotDuplicated = !document.getElementById('document-feedback')?.classList.contains('!flex');
        const allLocalErrorEls = Array.from(document.querySelectorAll('p:has(> .error-text)'));
        const areAllLocalValid = allLocalErrorEls.every(p => p.classList.contains('hidden'));
        const allValid = lengthValid && caseValid && numValid && specValid && repValid && spaceValid && idValid && isMatch && isNotDuplicated && areAllLocalValid;

        if (submitBtn) {
            submitBtn.disabled = !allValid;
            submitBtn.classList.toggle('opacity-50', !allValid);
            submitBtn.classList.toggle('cursor-not-allowed', !allValid);
        }
    }

    document.getElementById('reg-password')?.addEventListener('input', checkPasswordMatch);
    document.getElementById('reg-password-confirm')?.addEventListener('input', checkPasswordMatch);

    // ── AJAX Documento ────────────────────────────────────────────────
    const docTypeEl  = document.getElementById('document_type');
    const docNumEl   = document.getElementById('document_number');
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
            const type   = docTypeEl.value;
            const number = docNumEl.value.trim();
            if (!number) { feedbackEl.classList.remove('!flex'); validationErrorEl.classList.add('hidden'); checkPasswordMatch(); return; }

            let localError = '';
            if (/^(\d)\1+$/.test(number)) localError = 'Secuencia numérica repetida inválida.';
            else if ((type === 'V' || type === 'E') && (number.length < 6 || number.length > 9)) localError = 'El documento debe tener entre 6 y 9 números.';
            else if ((type === 'J' || type === 'G') && (number.length < 6 || number.length > 9)) localError = 'El RIF debe tener entre 6 y 9 números.';

            if (localError) {
                validationErrorEl.querySelector('.error-text').textContent = localError;
                validationErrorEl.classList.remove('hidden');
                feedbackEl.classList.remove('!flex'); feedbackEl.classList.add('hidden');
                checkPasswordMatch(); return;
            } else { validationErrorEl.classList.add('hidden'); }

            fetch('{{ route('api.check-document') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ tipo_documento: type, documento_identidad: number })
            }).then(r => r.json()).then(data => {
                feedbackEl.classList.toggle('!flex', data.exists);
                feedbackEl.classList.toggle('hidden', !data.exists);
                checkPasswordMatch();
            }).catch(e => console.error(e));
        }, 500);
    }
    docTypeEl?.addEventListener('change', checkDocumentExists);
    docNumEl?.addEventListener('blur', checkDocumentExists);

    // ── Teléfono ──────────────────────────────────────────────────────
    const phoneNumEl     = document.querySelector('input[name="telefono_numero"]');
    const phoneFeedbackEl= document.getElementById('phone-feedback');
    phoneNumEl?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        const val = this.value;
        let localError = '';
        if (val.length > 0 && val.length < 7) localError = 'El número debe tener 7 dígitos completos.';
        else if (/^(\d)\1+$/.test(val) && val.length > 0) localError = 'Secuencia numérica repetida inválida.';
        if (localError && val.length > 0) {
            phoneFeedbackEl.querySelector('.error-text').textContent = localError;
            phoneFeedbackEl.classList.remove('hidden'); phoneFeedbackEl.classList.add('flex');
        } else { phoneFeedbackEl.classList.add('hidden'); phoneFeedbackEl.classList.remove('flex'); }
        checkPasswordMatch();
    });
    phoneNumEl?.addEventListener('blur', function() {
        if (!this.value) { phoneFeedbackEl.classList.add('hidden'); phoneFeedbackEl.classList.remove('flex'); checkPasswordMatch(); }
    });

    // ── Validación email/password en tiempo real ──────────────────────
    document.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
        const errorEl = document.createElement('p');
        errorEl.className = 'hidden text-xs text-rose-500 mt-1.5 font-bold items-center gap-1';
        errorEl.innerHTML = '<span class="material-symbols-outlined text-[14px]">error</span> <span class="error-text"></span>';
        if (input.type === 'password' && input.parentNode.classList.contains('relative')) {
            input.parentNode.parentNode.appendChild(errorEl);
        } else { input.parentNode.appendChild(errorEl); }

        const check = () => {
            const val = input.value;
            let msg = '';
            if (val.length === 0) { errorEl.classList.add('hidden'); errorEl.classList.remove('flex'); if(input.id === 'reg-password' || input.id === 'reg-password-confirm') checkPasswordMatch(); return; }
            if (/\s/.test(val)) msg = 'No se permiten espacios en este campo.';
            else if (input.type === 'email' && !/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(val)) msg = 'Formato de correo inválido (ej. usuario@dominio.com)';
            if (msg) {
                errorEl.querySelector('.error-text').textContent = msg;
                errorEl.classList.remove('hidden'); errorEl.classList.add('flex');
                if (input.id === 'reg-password' || input.id === 'reg-password-confirm') {
                    const btn = document.getElementById('btn-registro');
                    if(btn) { btn.disabled = true; btn.classList.add('opacity-50','cursor-not-allowed'); }
                }
            } else { errorEl.classList.add('hidden'); errorEl.classList.remove('flex'); if(input.id === 'reg-password' || input.id === 'reg-password-confirm') checkPasswordMatch(); }
        };
        input.addEventListener('input', check);
        input.addEventListener('blur', check);
    });

    checkPasswordMatch();
</script>
@endpush
