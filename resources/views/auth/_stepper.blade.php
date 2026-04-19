{{--
    Partial: auth/_stepper.blade.php
    Props: $step (1, 2, 3 o 4)
--}}
@php
    $steps = [
        1 => ['icon' => 'badge',       'label' => 'Identifícate'],
        2 => ['icon' => 'alternate_email', 'label' => 'Método'],
        3 => ['icon' => 'pin',         'label' => 'Código'],
        4 => ['icon' => 'lock_reset',  'label' => 'Nueva clave'],
    ];
@endphp

<div style="display:flex; align-items:center; gap:0; margin-bottom:32px;">
    @foreach($steps as $n => $s)

    {{-- Paso --}}
    <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
        {{-- Círculo --}}
        <div style="
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: {{ $n < $step ? '#7c3aed' : ($n === $step ? '#ede9fe' : '#f3f4f6') }};
            border: 2px solid {{ $n <= $step ? '#7c3aed' : '#e5e7eb' }};
            transition: all .3s;
        ">
            @if($n < $step)
                <span class="material-symbols-outlined" style="font-size:16px; color:white; font-variation-settings:'FILL' 1;">check</span>
            @elseif($n === $step)
                <span class="material-symbols-outlined" style="font-size:16px; color:#7c3aed;">{{ $s['icon'] }}</span>
            @else
                <span style="font-size:12px; font-weight:700; color:#9ca3af;">{{ $n }}</span>
            @endif
        </div>
        {{-- Label --}}
        <span style="font-size:10px; font-weight:{{ $n === $step ? '700' : '500' }}; color:{{ $n === $step ? '#7c3aed' : ($n < $step ? '#6d28d9' : '#9ca3af') }}; margin-top:5px; white-space:nowrap;">{{ $s['label'] }}</span>
    </div>

    {{-- Conector (excepto el último) --}}
    @if($n < count($steps))
    <div style="
        flex: 1;
        height: 2px;
        margin: 0 4px;
        margin-bottom: 20px;
        background: {{ $n < $step ? '#7c3aed' : '#e5e7eb' }};
        border-radius: 2px;
        transition: background .3s;
    "></div>
    @endif

    @endforeach
</div>
