@extends('layouts.auth')
@section('title', 'Verificar código')
@section('content')

@include('auth._stepper', ['step' => 3])

<div style="margin-bottom: 24px;">
    <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">Código de verificación</h1>
    <p style="color:#6b7280; font-size:14px; line-height:1.5;">Ingresa el código de 6 dígitos que enviamos a tu correo electrónico.</p>
</div>

@if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span>
        {{ $errors->first() }}
    </div>
@endif

<div id="expiration-container" class="hidden mb-5 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">timer_off</span>
    El código expiró. Solicita uno nuevo abajo.
</div>

<form method="POST" action="{{ route('password.verify') }}" class="space-y-8" id="verify-form">
    @csrf

    {{-- OTP Inputs --}}
    <div>
        <label class="auth-label text-center block mb-5">Código temporal</label>
        <div class="flex justify-center items-center gap-2.5">
            @for($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1"
                   class="otp-input"
                   style="width:48px; height:56px; text-align:center; font-size:1.5rem; font-weight:800; color:#1e1b4b;
                          border-radius:14px; border:2px solid #e5e7eb; background:#fafafa; outline:none;
                          transition:border-color .15s, box-shadow .15s;"
                   {{ $i === 0 ? 'autofocus' : '' }}>
            @endfor

            <button type="button" class="toggle-visibility ml-2 text-slate-400 hover:text-primary transition-colors" title="Ver código">
                <span class="material-symbols-outlined text-[22px]">visibility</span>
            </button>
        </div>
        <input type="hidden" name="code" id="actual-code" required>
    </div>

    {{-- Timer / reenvío --}}
    <div class="text-center" style="color:#6b7280; font-size:13px;">
        ¿No llegó el código? Espera <span id="timer" style="font-weight:800; color:#7c3aed;">136</span>s o
        <button type="button" id="btn-resend"
                class="font-bold text-slate-400 cursor-not-allowed pointer-events-none transition-colors"
                onclick="document.getElementById('form-resend').submit();">
            reenviar código
        </button>
    </div>

    <button type="submit" class="auth-btn" id="btn-verify" disabled
            style="opacity:.4; cursor:not-allowed;">
        Verificar código
        <span class="material-symbols-outlined text-[18px]" style="margin-left:6px; vertical-align:-4px;">verified</span>
    </button>
</form>

<form id="form-resend" action="{{ route('password.send_code') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="method" value="email">
</form>

<div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
    <a href="{{ route('password.selection') }}" class="flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Otro método
    </a>
</div>

<style>
    .otp-input:focus {
        border-color: #7c3aed !important;
        box-shadow: 0 0 0 3px rgba(124,58,237,0.15) !important;
        background: #fff !important;
    }
    .otp-filled {
        border-color: #7c3aed !important;
        background: #faf5ff !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const demoCode = "{{ env('APP_ENV') == 'local' ? Cache::get('recovery_otp_'.session('recovery_user_id')) : '' }}";
    if (demoCode) console.log('%c[DEV] OTP: ' + demoCode, 'color:#fff; background:#7c3aed; padding:4px 8px; border-radius:4px; font-weight:bold;');

    const inputs     = document.querySelectorAll('.otp-input');
    const hidden     = document.getElementById('actual-code');
    const btnVerify  = document.getElementById('btn-verify');
    const visBtn     = document.querySelector('.toggle-visibility');
    const form       = document.getElementById('verify-form');
    let   isVisible  = true;

    function updateCode() {
        let code = '';
        inputs.forEach(i => code += i.value);
        hidden.value = code;
        const filled = code.length === 6;
        btnVerify.disabled = !filled;
        btnVerify.style.opacity = filled ? '1' : '.4';
        btnVerify.style.cursor  = filled ? 'pointer' : 'not-allowed';
    }

    inputs.forEach((input, idx) => {
        input.addEventListener('input', e => {
            input.value = input.value.replace(/[^0-9]/g,'').slice(-1);
            input.classList.toggle('otp-filled', input.value !== '');
            updateCode();
            if (input.value && idx < inputs.length - 1) inputs[idx + 1].focus();
            if (idx === inputs.length - 1 && input.value) { updateCode(); if (hidden.value.length === 6) form.submit(); }
        });
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && idx > 0) { inputs[idx-1].focus(); inputs[idx-1].value = ''; inputs[idx-1].classList.remove('otp-filled'); updateCode(); }
        });
        input.addEventListener('paste', e => {
            e.preventDefault();
            const paste = (e.clipboardData.getData('text') || '').replace(/\D/g,'').slice(0,6);
            [...paste].forEach((c, i) => { if(inputs[i]) { inputs[i].value = c; inputs[i].classList.add('otp-filled'); }});
            updateCode();
            if (paste.length >= 6) { form.submit(); } else inputs[Math.min(paste.length, 5)].focus();
        });
    });

    visBtn.addEventListener('click', () => {
        isVisible = !isVisible;
        visBtn.querySelector('span').textContent = isVisible ? 'visibility' : 'visibility_off';
        inputs.forEach(i => { i.style.webkitTextSecurity = isVisible ? 'none' : 'disc'; });
    });

    // Timer
    let timeLeft = 136;
    const timerEl  = document.getElementById('timer');
    const btnResend= document.getElementById('btn-resend');
    const expiryEl = document.getElementById('expiration-container');
    const interval = setInterval(() => {
        timeLeft--;
        timerEl.textContent = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(interval);
            btnResend.classList.remove('text-slate-400','cursor-not-allowed','pointer-events-none');
            btnResend.classList.add('text-violet-600','cursor-pointer','hover:underline');
            expiryEl.classList.remove('hidden');
        }
    }, 1000);
});
</script>
@endsection
