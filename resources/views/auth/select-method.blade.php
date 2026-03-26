@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="w-full max-w-[400px] mx-auto bg-white shadow-2xl shadow-slate-200/50 overflow-hidden rounded-[1.75rem] border border-slate-100">

    <div class="px-7 pt-10 pb-8">

        {{-- Encabezado --}}
        <div class="text-center mb-10 px-2">
            <h1 class="text-slate-900 text-[1.35rem] font-extrabold tracking-tight leading-snug">
                ¿Dónde quieres recibir el código para<br>restablecer la contraseña?
            </h1>
        </div>

        <form method="POST" action="{{ route('password.send_code') }}" id="recovery-form">
            @csrf

            @php
                $options = [
                    [
                        'id' => 'sms',
                        'icon' => 'sms',
                        'iconColor' => '#60A5FA', // Blue
                        'label' => 'Enviar código por SMS',
                        'value' => 'No registrado',
                    ],
                    [
                        'id' => 'email',
                        'icon' => 'mail_outline',
                        'iconColor' => '#F59E0B', // Amber
                        'label' => 'Enviar código por correo electrónico',
                        'value' => $maskedEmail ?? 'v*********@gmail.com',
                        'checked' => true,
                    ],
                    [
                        'id' => 'call',
                        'icon' => 'call',
                        'iconColor' => '#94A3B8', // Slate
                        'label' => 'Recibir código por llamada',
                        'value' => 'No registrado',
                    ],
                    [
                        'id' => 'whatsapp',
                        'icon' => 'whatsapp',
                        'iconColor' => '#25D366', // Emerald
                        'label' => 'Enviar código por WhatsApp',
                        'value' => 'No registrado',
                        'is_svg' => true,
                    ],
                ];
            @endphp

            <div class="space-y-1">
                @foreach($options as $opt)
                    @php
                        $isChecked = !empty($opt['checked']);
                    @endphp

                    <label for="method_{{ $opt['id'] }}" 
                           class="method-option relative flex items-center gap-4 cursor-pointer p-4 rounded-2xl hover:bg-slate-50/80 transition-all duration-200
                                  {{ $isChecked ? 'is-selected ring-1 ring-[#8b5cf6]/20 bg-slate-50/50' : '' }}">
                        
                        {{-- Radio Input --}}
                        <input type="radio" name="method" id="method_{{ $opt['id'] }}" value="{{ $opt['id'] }}" 
                               class="sr-only" {{ $isChecked ? 'checked' : '' }}>
                        
                        {{-- Círculo --}}
                        <div class="radio-outer flex-shrink-0 flex items-center justify-center w-[22px] h-[22px] rounded-full border-[2px] transition-colors duration-200
                                    {{ $isChecked ? 'border-[#8b5cf6]' : 'border-slate-200' }}">
                            {{-- Puntito interno --}}
                            <div class="radio-inner w-2.5 h-2.5 rounded-full bg-[#8b5cf6] transition-transform duration-200 transform
                                        {{ $isChecked ? 'scale-100' : 'scale-0' }}"></div>
                        </div>

                        {{-- Ícono --}}
                        <div class="flex-shrink-0 flex items-center justify-center w-8 h-8" style="color: {{ $opt['iconColor'] }};">
                            @if(!empty($opt['is_svg']))
                                <svg class="w-[1.5rem] h-[1.5rem] fill-current" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            @else
                                <span class="material-symbols-outlined text-[1.65rem] shrink-0" style="font-variation-settings: 'FILL' 0, 'wght' 300">{{ $opt['icon'] }}</span>
                            @endif
                        </div>

                        {{-- Textos dinámicos --}}
                        <div class="min-w-0 flex-1 ml-1">
                            <p class="text-label text-[0.95rem] font-medium leading-[1.3] transition-colors duration-200
                                      {{ $isChecked ? 'text-slate-500' : 'text-slate-400' }}">
                                {{ $opt['label'] }}
                            </p>
                            <p class="text-value text-[0.95rem] font-bold leading-[1.3] transition-colors duration-200 mt-0.5
                                      {{ $isChecked ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ $opt['value'] }}
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>

            {{-- Botón Continuar al final --}}
            <div class="mt-8">
                <button type="submit"
                        class="w-full bg-[#8b5cf6] hover:bg-[#7c3aed] text-white font-semibold py-[1.1rem] rounded-2xl
                               shadow-md shadow-[#8b5cf6]/25 hover:shadow-lg hover:shadow-[#8b5cf6]/40 hover:-translate-y-[1px]
                               transition-all duration-300 text-[1.05rem]">
                    Continuar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const options = document.querySelectorAll('.method-option');
        
        options.forEach(option => {
            const input = option.querySelector('input[type="radio"]');
            
            option.addEventListener('click', () => {
                input.checked = true;

                // Resetear todas las opciones
                options.forEach(opt => {
                    opt.classList.remove('is-selected', 'ring-1', 'ring-[#8b5cf6]/20', 'bg-slate-50/50');
                    
                    const outer = opt.querySelector('.radio-outer');
                    const inner = opt.querySelector('.radio-inner');
                    if(outer) {
                        outer.classList.remove('border-[#8b5cf6]');
                        outer.classList.add('border-slate-200');
                    }
                    if(inner) {
                        inner.classList.remove('scale-100');
                        inner.classList.add('scale-0');
                    }

                    const textLabel = opt.querySelector('.text-label');
                    const textValue = opt.querySelector('.text-value');
                    if(textLabel) {
                        textLabel.classList.remove('text-slate-500');
                        textLabel.classList.add('text-slate-400');
                    }
                    if(textValue) {
                        textValue.classList.remove('text-slate-900');
                        textValue.classList.add('text-slate-400');
                    }
                });

                // Activar opción seleccionada
                option.classList.add('is-selected', 'ring-1', 'ring-[#8b5cf6]/20', 'bg-slate-50/50');
                
                const outer = option.querySelector('.radio-outer');
                const inner = option.querySelector('.radio-inner');
                if(outer) {
                    outer.classList.remove('border-slate-200');
                    outer.classList.add('border-[#8b5cf6]');
                }
                if(inner) {
                    inner.classList.remove('scale-0');
                    inner.classList.add('scale-100');
                }

                const textLabel = option.querySelector('.text-label');
                const textValue = option.querySelector('.text-value');
                if(textLabel) {
                    textLabel.classList.remove('text-slate-400');
                    textLabel.classList.add('text-slate-500');
                }
                if(textValue) {
                    textValue.classList.remove('text-slate-400');
                    textValue.classList.add('text-slate-900');
                }
            });
        });
    });
</script>
@endsection
