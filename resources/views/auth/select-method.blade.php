@extends('layouts.auth')
@section('title', 'Recuperar Contraseña — Método')
@section('content')

@include('auth._stepper', ['step' => 2])

<div style="margin-bottom: 24px;">
    <h1 style="font-size:1.7rem; font-weight:900; color:#1e1b4b; letter-spacing:-0.5px; margin-bottom:6px;">Selecciona cómo recuperar acceso</h1>
    <p style="color:#6b7280; font-size:14px;">¿Dónde quieres recibir tu código de verificación temporal?</p>
</div>

<form method="POST" action="{{ route('password.send_code') }}" id="recovery-form">
    @csrf

    @php
        $options = [
            [
                'id'        => 'email',
                'icon'      => 'mail_outline',
                'iconBg'    => '#FEF3C7',
                'iconColor' => '#D97706',
                'label'     => 'Código por correo electrónico',
                'value'     => $maskedEmail ?? 'v*****@gmail.com',
                'checked'   => true,
            ],
        ];
    @endphp

    <div class="space-y-3 mb-7">
        @foreach($options as $opt)
        @php $checked = !empty($opt['checked']); @endphp
        <label for="method_{{ $opt['id'] }}"
               class="method-option flex items-center gap-4 cursor-pointer p-4 rounded-2xl border-2 transition-all duration-200 {{ $checked ? 'border-violet-500 bg-violet-50/60' : 'border-slate-200 hover:border-violet-300 hover:bg-slate-50' }}">

            <input type="radio" name="method" id="method_{{ $opt['id'] }}" value="{{ $opt['id'] }}"
                   class="sr-only" {{ $checked ? 'checked' : '' }}>

            {{-- Radio visual --}}
            <div class="radio-outer flex-shrink-0 flex items-center justify-center w-5 h-5 rounded-full border-2 transition-all {{ $checked ? 'border-violet-600' : 'border-slate-300' }}">
                <div class="radio-inner w-2.5 h-2.5 rounded-full bg-violet-600 transition-transform {{ $checked ? 'scale-100' : 'scale-0' }}"></div>
            </div>

            {{-- Ícono --}}
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:{{ $opt['iconBg'] }}; color:{{ $opt['iconColor'] }};">
                <span class="material-symbols-outlined text-[22px]">{{ $opt['icon'] }}</span>
            </div>

            {{-- Texto --}}
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-medium text-label {{ $checked ? 'text-slate-500' : 'text-slate-400' }}">{{ $opt['label'] }}</p>
                <p class="font-bold text-sm text-value {{ $checked ? 'text-slate-900' : 'text-slate-400' }}">{{ $opt['value'] }}</p>
            </div>
        </label>
        @endforeach
    </div>

    <button type="submit" class="auth-btn">
        Enviar código
        <span class="material-symbols-outlined text-[18px]" style="margin-left:6px; vertical-align:-4px;">send</span>
    </button>
</form>

<div class="mt-6 pt-5 border-t border-slate-100">
    <a href="{{ route('password.request') }}" class="flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver al paso anterior
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.method-option').forEach(option => {
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            radio.checked = true;

            document.querySelectorAll('.method-option').forEach(opt => {
                const isSelected = opt === option;
                opt.classList.toggle('border-violet-500', isSelected);
                opt.classList.toggle('bg-violet-50/60', isSelected);
                opt.classList.toggle('border-slate-200', !isSelected);
                const outer = opt.querySelector('.radio-outer');
                const inner = opt.querySelector('.radio-inner');
                outer?.classList.toggle('border-violet-600', isSelected);
                outer?.classList.toggle('border-slate-300', !isSelected);
                inner?.classList.toggle('scale-100', isSelected);
                inner?.classList.toggle('scale-0', !isSelected);
                opt.querySelector('.text-label')?.classList.toggle('text-slate-500', isSelected);
                opt.querySelector('.text-label')?.classList.toggle('text-slate-400', !isSelected);
                opt.querySelector('.text-value')?.classList.toggle('text-slate-900', isSelected);
                opt.querySelector('.text-value')?.classList.toggle('text-slate-400', !isSelected);
            });
        });
    });
});
</script>
@endsection
