@extends('layouts.admin')

@section('title', 'Configuración Tasa BCV')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Configuración Bi-monetaria (BCV)</h2>
            <p class="text-slate-500 text-sm mt-1">Administra la forma en que la tienda calcula la paridad USD/Bs.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Volver
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 font-medium text-sm shadow-sm animate-fade-in">
            <span class="material-symbols-outlined text-xl text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl shadow-sm animate-fade-in">
            <div class="flex items-center gap-3 font-medium text-sm mb-2">
                <span class="material-symbols-outlined text-xl text-red-500">error</span>
                Error al guardar los cambios:
            </div>
            <ul class="list-disc list-inside text-xs pl-8">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in relative">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
            <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner border border-primary/20">
                <span class="material-symbols-outlined text-[26px]">currency_exchange</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Tasa de Cambio Global</h3>
                <p class="text-xs text-slate-400 font-medium">Esta tasa afectará nuevos pedidos en toda la plataforma.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.bcv.update') }}" method="POST" class="p-8">
            @csrf
            
            <div class="space-y-8">
                {{-- Selector de Modo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="modo_tasa" value="auto" class="peer sr-only" {{ !$config->usar_tasa_manual ? 'checked' : '' }} onchange="toggleManualInput(false)">
                        <div class="h-full p-5 rounded-2xl border-2 border-slate-100 hover:border-primary/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                            <span class="material-symbols-outlined text-3xl mb-2 text-slate-400 peer-checked:text-primary transition-colors block">autorenew</span>
                            <span class="block font-bold text-slate-700 peer-checked:text-primary">Tasa BCV Automática</span>
                            <span class="block text-xs text-slate-400 mt-1 line-clamp-2">Sincronización diaria con la API gubernamental. (Recomendado)</span>
                        </div>
                        <div class="hidden peer-checked:flex absolute top-3 right-3 size-5 rounded-full bg-primary items-center justify-center shadow-lg text-white">
                            <span class="material-symbols-outlined text-[12px] font-bold">check</span>
                        </div>
                    </label>

                    <label class="cursor-pointer relative group">
                        <input type="radio" name="modo_tasa" value="manual" class="peer sr-only" {{ $config->usar_tasa_manual ? 'checked' : '' }} onchange="toggleManualInput(true)">
                        <div class="h-full p-5 rounded-2xl border-2 border-slate-100 hover:border-amber-400/30 peer-checked:border-amber-400 peer-checked:bg-amber-50/50 transition-all text-center">
                            <span class="material-symbols-outlined text-3xl mb-2 text-slate-400 peer-checked:text-amber-500 transition-colors block">edit_document</span>
                            <span class="block font-bold text-slate-700 peer-checked:text-amber-600">Fijar Tasa Manual</span>
                            <span class="block text-xs text-slate-400 mt-1 line-clamp-2">Ignorar API y usar la tasa ingresada abajo. (Para emergencias)</span>
                        </div>
                        <div class="hidden peer-checked:flex absolute top-3 right-3 size-5 rounded-full bg-amber-400 items-center justify-center shadow-lg text-white">
                            <span class="material-symbols-outlined text-[12px] font-bold">check</span>
                        </div>
                    </label>
                </div>
                
                {{-- Checkbox Oculto para sincronizar backend --}}
                <input type="checkbox" id="usar_tasa_manual" name="usar_tasa_manual" value="1" class="hidden" {{ $config->usar_tasa_manual ? 'checked' : '' }}>

                {{-- Input Tasa Manual --}}
                <div id="manual_input_container" class="bg-amber-50 rounded-2xl p-6 border border-amber-100/50 flex flex-col items-center justify-center transition-all duration-300 {{ !$config->usar_tasa_manual ? 'hidden opacity-0' : 'opacity-100' }}">
                    <label class="block text-xs font-bold text-amber-700/60 uppercase tracking-widest mb-3 text-center">Tasa Fija (Bs/USD)</label>
                    <div class="relative w-48">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-amber-700/40 font-black">Bs.</span>
                        <input type="number" step="0.01" min="1" name="tasa_bcv_manual" value="{{ $config->tasa_bcv_manual }}" 
                               class="w-full bg-white border border-amber-200/50 rounded-xl py-3 pl-12 pr-4 text-center font-black text-amber-700 text-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 placeholder-amber-200 transition-all"
                               placeholder="36.50">
                    </div>
                    <p class="text-xs text-amber-600 mt-3 text-center px-4 max-w-sm">Esta tasa se congelará en cualquier compra realizada por los clientes de forma inmediata al hacer clic en Guardar.</p>
                </div>

                {{-- Botón Guardar --}}
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-black transition-all shadow-lg shadow-slate-900/15 flex items-center gap-2 hover:scale-[1.02] active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Guardar Configuración
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Widget Informativo --}}
    <div class="mt-8 bg-slate-800 rounded-3xl p-6 border border-slate-700 text-slate-300 flex items-start gap-4 shadow-xl">
        <span class="material-symbols-outlined text-4xl text-emerald-400">info</span>
        <div>
            <h4 class="text-white font-bold mb-1">Estado de la Tasa Actual en la Tienda:</h4>
            <div class="flex items-end gap-3 mt-2">
                <span class="text-3xl font-black text-white">Bs. {{ number_format(bcv_rate(), 2, ',', '.') }}</span>
                <span class="text-xs font-medium text-emerald-400 uppercase tracking-wider mb-1 px-2 py-0.5 rounded-md bg-emerald-400/20 border border-emerald-400/30">Valor Activo</span>
            </div>
            <p class="text-xs text-slate-400 mt-3">Las facturas ya emitidas NO se verán afectadas al cambiar esta configuración. Cada orden almacena su propia tasa al momento de realizarse la compra.</p>
        </div>
    </div>
</div>

<script>
    function toggleManualInput(isManual) {
        const container = document.getElementById('manual_input_container');
        const checkbox = document.getElementById('usar_tasa_manual');
        
        checkbox.checked = isManual;

        if (isManual) {
            container.classList.remove('hidden');
            // Timeout to allow display:block to apply before changing opacity
            setTimeout(() => {
                container.classList.remove('opacity-0');
                container.classList.add('opacity-100');
            }, 10);
        } else {
            container.classList.remove('opacity-100');
            container.classList.add('opacity-0');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 300);
        }
    }
</script>

@endsection
