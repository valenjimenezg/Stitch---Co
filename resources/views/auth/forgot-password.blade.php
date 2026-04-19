@extends('layouts.auth')
@section('title', 'Recuperar Contraseña')
@section('content')

{{-- ══════════════════════════════════════════ --}}
{{-- PASO 1 de 3 — Identificación              --}}
{{-- ══════════════════════════════════════════ --}}

{{-- Stepper --}}
@include('auth._stepper', ['step' => 1])

<div style="margin-bottom: 28px;">
    <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">Recupera tu acceso</h1>
    <p style="color:#6b7280; font-size:14px; line-height:1.5;">Ingresa tu documento de identidad y correo para continuar con el proceso de recuperación.</p>
</div>

@if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span>
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('password.identify') }}" class="space-y-5">
    @csrf

    {{-- Documento --}}
    <div>
        <label class="auth-label">Documento de Identidad</label>
        <div class="flex">
            <select name="document_type" id="document_type"
                    class="auth-input rounded-r-none border-r-0" style="width:72px; flex-shrink:0;">
                <option value="V" {{ old('document_type') == 'V' ? 'selected' : '' }}>V</option>
                <option value="E" {{ old('document_type') == 'E' ? 'selected' : '' }}>E</option>
                <option value="J" {{ old('document_type') == 'J' ? 'selected' : '' }}>J</option>
                <option value="G" {{ old('document_type') == 'G' ? 'selected' : '' }}>G</option>
            </select>
            <input name="document_number" id="document_number" type="text" value="{{ old('document_number') }}"
                   class="auth-input rounded-l-none flex-1 border-l-0"
                   placeholder="12345678" required autofocus/>
        </div>
        <p id="document-error" class="hidden text-xs text-rose-500 mt-1.5 font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">error</span> <span id="doc-error-text"></span>
        </p>
    </div>

    {{-- Usuario / Correo --}}
    <div>
        <label class="auth-label">Correo electrónico (Usuario)</label>
        <div class="relative">
            <input name="usuario" type="text" value="{{ old('usuario') }}"
                   class="auth-input pr-12"
                   placeholder="ejemplo@correo.com" required/>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
        </div>
    </div>

    <button type="submit" id="btn-submit" class="auth-btn">
        Continuar
        <span class="material-symbols-outlined text-[18px]" style="margin-left:6px; vertical-align:-4px;">arrow_forward</span>
    </button>
</form>

<div class="mt-6 pt-5 border-t border-slate-100">
    <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver al inicio de sesión
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const docTypeEl  = document.getElementById('document_type');
    const docNumEl   = document.getElementById('document_number');
    const docErrorEl = document.getElementById('document-error');
    const docErrTxt  = document.getElementById('doc-error-text');
    const btnSubmit  = document.getElementById('btn-submit');

    docNumEl.addEventListener('input', function() { this.value = this.value.replace(/[^0-9]/g, ''); });

    function validate() {
        const type = docTypeEl.value;
        const num  = docNumEl.value.trim();
        if (!num) { docErrorEl.classList.add('hidden'); btnSubmit.disabled = false; return; }
        let error = '';
        if ((type === 'V' || type === 'E') && (num.length < 6 || num.length > 8)) error = 'La cédula debe tener entre 6 y 8 números.';
        else if ((type === 'J' || type === 'G') && num.length !== 9) error = 'El RIF debe tener 9 números.';

        if (error) {
            docErrTxt.textContent = error;
            docErrorEl.classList.remove('hidden');
            btnSubmit.disabled = true; btnSubmit.classList.add('opacity-50','cursor-not-allowed');
        } else {
            docErrorEl.classList.add('hidden');
            btnSubmit.disabled = false; btnSubmit.classList.remove('opacity-50','cursor-not-allowed');
        }
    }
    docNumEl.addEventListener('blur', validate);
    docTypeEl.addEventListener('change', validate);
});
</script>
@endsection
