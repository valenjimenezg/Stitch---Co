@extends('layouts.auth')
@section('title', 'Nueva Contraseña')
@section('content')

@include('auth._stepper', ['step' => 4])

<div style="margin-bottom: 24px;">
    <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">Crea tu nueva contraseña</h1>
    <p style="color:#6b7280; font-size:14px;">Recuerda no usar información personal como tu año de nacimiento.</p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="space-y-5">
    @csrf

    <div>
        <label class="auth-label">Nueva contraseña</label>
        <div class="relative">
            <input name="password" type="password" id="pass1" required
                   class="auth-input pr-12"
                   placeholder="••••••••••"/>
            <button type="button" onclick="togg('pass1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors outline-none">
                <span class="material-symbols-outlined text-[20px] select-none">visibility_off</span>
            </button>
        </div>
    </div>

    <div>
        <label class="auth-label">Confirmar contraseña</label>
        <div class="relative">
            <input name="password_confirmation" type="password" id="pass2" required
                   class="auth-input pr-12"
                   placeholder="••••••••••"/>
            <button type="button" onclick="togg('pass2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors outline-none">
                <span class="material-symbols-outlined text-[20px] select-none">visibility_off</span>
            </button>
        </div>
        <p id="password-match-error" class="hidden text-xs text-rose-500 mt-1.5 font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">error</span> Las contraseñas no coinciden.
        </p>
    </div>

    {{-- Panel de fortaleza estilo Google/Mercantil --}}
    <div id="pwd-strength-panel" style="display:none; background:#fafafa; border:1.5px solid #ede9fe; border-radius:18px; padding:16px 18px;">

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

        {{-- Requisitos checklist --}}
        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; margin-bottom:10px;">Requisitos</p>
        <ul style="display:grid; grid-template-columns:1fr 1fr; gap:7px 12px;">
            <li id="req-length"  class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Entre <strong>8 y 15</strong> caracteres</span></li>
            <li id="req-case"    class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Mayúscula y minúscula</span></li>
            <li id="req-num"     class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Al menos un número</span></li>
            <li id="req-spec"    class="pwd-req"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Un símbolo <strong>*.-_@#</strong></span></li>
            <li id="req-no-rep"  class="pwd-req" style="grid-column:span 2;"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Sin secuencias (abc, 123, aaa)</span></li>
            <li id="req-match"   class="pwd-req" style="grid-column:span 2; border-top:1px solid #f0ebff; padding-top:7px; margin-top:2px;"><span class="req-icon material-symbols-outlined">radio_button_unchecked</span><span>Ambas contraseñas <strong>coinciden</strong></span></li>
        </ul>
    </div>

    <button type="submit" id="btn-submit" class="auth-btn" disabled style="opacity:.4; cursor:not-allowed;">
        Actualizar contraseña
        <span class="material-symbols-outlined text-[18px]" style="margin-left:6px; vertical-align:-4px;">lock_reset</span>
    </button>
</form>

<div class="mt-6 pt-5 border-t border-slate-100">
    <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Cancelar y volver
    </a>
</div>

<style>
.pwd-req {
    display: flex; align-items: flex-start; gap: 6px;
    font-size: 12px; font-weight: 500; color: #6b7280;
    transition: color .2s;
}
.pwd-req .req-icon {
    font-size: 15px; color: #d1d5db; flex-shrink: 0; margin-top: 1px;
    transition: color .2s; font-variation-settings: 'FILL' 0;
}
.pwd-req.valid { color: #059669; }
.pwd-req.valid .req-icon { color: #10b981; font-variation-settings: 'FILL' 1; }
.pwd-req.invalid { color: #ef4444; }
.pwd-req.invalid .req-icon { color: #ef4444; }
</style>

<script>
    function togg(id) {
        const x = document.getElementById(id);
        const icon = x.nextElementSibling.querySelector('span');
        x.type = x.type === 'password' ? 'text' : 'password';
        icon.textContent = x.type === 'password' ? 'visibility_off' : 'visibility';
    }

    function checkPasswordMatch() {
        const pwd     = document.getElementById('pass1')?.value || '';
        const confirm = document.getElementById('pass2')?.value || '';
        const btn     = document.getElementById('btn-submit');
        const errMsg  = document.getElementById('password-match-error');

        const lengthValid = pwd.length >= 8 && pwd.length <= 15;
        const caseValid   = /[a-z]/.test(pwd) && /[A-Z]/.test(pwd);
        const numValid    = /[0-9]/.test(pwd);
        const specValid   = /[*.\-_@#!$%&?+/]/.test(pwd);
        const lowerPwd    = pwd.toLowerCase();
        const sequences   = [
            '012','123','234','345','456','567','678','789',
            'abc','bcd','cde','def','efg','fgh','ghi','hij'
        ];
        const repValid  = !/(.)\1\1/.test(pwd) && pwd.length > 0 && !sequences.some(s => lowerPwd.includes(s));
        const isMatch   = pwd !== '' && pwd === confirm;

        const setReq = (id, ok, typed) => {
            const el = document.getElementById(id);
            if (!el) return;
            const icon = el.querySelector('.req-icon');
            if (icon) icon.textContent = ok ? 'check_circle' : (typed ? 'cancel' : 'radio_button_unchecked');
            el.classList.toggle('valid',   ok);
            el.classList.toggle('invalid', !ok && typed);
        };
        const typed = pwd.length > 0;
        setReq('req-length', lengthValid, typed);
        setReq('req-case',   caseValid,   typed);
        setReq('req-num',    numValid,    typed);
        setReq('req-spec',   specValid,   typed);
        setReq('req-no-rep', repValid,    typed);
        setReq('req-match',  isMatch,     confirm.length > 0);

        // ── Barra de fortaleza ─────────────────────────────────────────
        const panel  = document.getElementById('pwd-strength-panel');
        if (panel) {
            panel.style.display = typed ? 'block' : 'none';
            const score  = [lengthValid, caseValid, numValid, specValid, repValid].filter(Boolean).length;
            const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
            const labels = ['Muy débil','Débil','Regular','Fuerte'];
            ['bar1','bar2','bar3','bar4'].forEach((bid, i) => {
                const bar = document.getElementById(bid);
                if (bar) bar.style.background = i < score ? colors[Math.min(score-1,3)] : '#e5e7eb';
            });
            const lbl = document.getElementById('pwd-strength-label');
            if (lbl) {
                lbl.textContent = typed ? labels[Math.min(score > 0 ? score-1 : 0, 3)] : '—';
                lbl.style.color = typed ? colors[Math.min(score > 0 ? score-1 : 0, 3)] : '#9ca3af';
            }
        }

        errMsg?.classList.toggle('hidden', confirm === '' || isMatch);

        const allOk = lengthValid && caseValid && numValid && specValid && repValid && isMatch;
        btn.disabled      = !allOk;
        btn.style.opacity = allOk ? '1' : '.4';
        btn.style.cursor  = allOk ? 'pointer' : 'not-allowed';
    }

    document.getElementById('pass1')?.addEventListener('input', checkPasswordMatch);
    document.getElementById('pass2')?.addEventListener('input', checkPasswordMatch);
    document.addEventListener('DOMContentLoaded', checkPasswordMatch);
</script>
@endsection
