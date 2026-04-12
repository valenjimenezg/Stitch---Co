@extends('layouts.app')

@section('title', 'Continuar Compra — Stitch & Co')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Validar Pago</h1>
            <p class="text-slate-500">Orden #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }} por {{ bs($venta->total_amount, false, $venta->tasa_bcv_aplicada) }} (Ref: ${{ number_format((float)$venta->total_amount, 2) }})</p>
        </div>
        <a href="{{ route('profile.orders') }}" class="text-sm font-bold text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-600 rounded-xl mb-6 text-sm border border-red-200">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-200/50 p-8">
        <form method="POST" action="{{ route('profile.orders.store_reference', $venta->id) }}">
            @csrf
            <div class="space-y-6">
                <div class="bg-primary/5 p-5 rounded-xl border border-primary/20 text-sm">
                    <p class="font-bold text-primary mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined">info</span> Instrucciones
                    </p>
                    <p class="text-slate-600">Por favor, introduce los números de confirmación enviados por tu banco para verificar la transacción de <strong>{{ str_replace('_', ' ', $venta->metodo_pago) }}</strong>.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor</label>
                    <select name="banco_pago" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 focus:ring-primary focus:border-primary">
                        <option value="">Selecciona tu banco</option>
                        <option value="0102 - Banco de Venezuela">0102 - Banco de Venezuela</option>
                        <option value="0134 - Banesco">0134 - Banesco</option>
                        <option value="0105 - Mercantil">0105 - Mercantil</option>
                        <option value="0108 - Provincial">0108 - Provincial</option>
                        <option value="0191 - BNC">0191 - BNC</option>
                        <option value="0172 - Bancamiga">0172 - Bancamiga</option>
                        <option value="0114 - Bancaribe">0114 - Bancaribe</option>
                        <option value="0115 - Banco Exterior">0115 - Banco Exterior</option>
                        <option value="0104 - Venezolano de Crédito">0104 - Venezolano de Crédito</option>
                        <option value="0128 - Banco Caroní">0128 - Banco Caroní</option>
                        <option value="0138 - Banco Plaza">0138 - Banco Plaza</option>
                        <option value="0151 - BFC (Fondo Común)">0151 - BFC (Fondo Común)</option>
                        <option value="0157 - Del Sur">0157 - Del Sur</option>
                        <option value="0171 - Banco Activo">0171 - Banco Activo</option>
                        <option value="0174 - Banplus">0174 - Banplus</option>
                        <option value="0137 - Sofitasa">0137 - Sofitasa</option>
                        <option value="0163 - Banco del Tesoro">0163 - Banco del Tesoro</option>
                        <option value="0175 - Digital de Trabajadores">0175 - Digital de Trabajadores (Bicentenario)</option>
                        <option value="0177 - BANFANB">0177 - BANFANB</option>
                        <option value="0166 - Banco Agrícola">0166 - Banco Agrícola</option>
                        <option value="0178 - N58 Banco Digital">0178 - N58 Banco Digital</option>
                        <option value="0169 - R4 Banco (Mi Banco)">0169 - R4 Banco (Mi Banco)</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700">Número de Referencia / Comprobante *</label>
                    <input name="referencia_pago" type="text" placeholder="Ej: 12345678" required class="w-full font-mono bg-slate-50 border border-slate-200 rounded-lg p-3 focus:ring-primary focus:border-primary text-lg" />
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full bg-primary text-white font-black px-8 py-4 rounded-xl shadow-lg shadow-primary/30 flex items-center justify-center gap-2 hover:bg-primary/90 transition-all active:scale-95">
                        <span class="material-symbols-outlined">check_circle</span>
                        Confirmar Pago
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
