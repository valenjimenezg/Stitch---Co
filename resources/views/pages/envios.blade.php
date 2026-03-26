@extends('layouts.app')

@section('title', 'Información de Envíos — Stitch & Co')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 mb-6">
            <span class="material-symbols-outlined text-4xl">local_shipping</span>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Política de Entregas</h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto">Llegamos a cada rincón de la ciudad. Conoce nuestros tiempos y costos de delivery local para asegurar que tu compra llegue a tus manos rápidamente en Guanare.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-12">
        <div class="p-8 sm:p-10">

            {{-- Tiempos de entrega --}}
            <div class="flex flex-col sm:flex-row gap-6 mb-12">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">schedule</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-3">Tiempos de Entrega</h3>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Los pedidos confirmados antes de las <strong>2:00 PM</strong> se enviarán ese mismo día hábil.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-slate-700 font-medium">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                            Guanare Casco Central: Entrega Mismo Día.
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 font-medium">
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                            Zonas Extraurbanas de Guanare: 24 a 48 horas hábiles.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="h-px w-full bg-slate-100 mb-12"></div>

            {{-- Costos y Tarifas --}}
            <div class="flex flex-col sm:flex-row gap-6 mb-12">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">payments</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-black text-slate-900 mb-3">Costos de Delivery</h3>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Manejamos tarifas accesibles para entregas en toda la ciudad de Guanare.
                    </p>
                    <div class="space-y-3">
                        {{-- Opción Estándar --}}
                        <div onclick="selectShipping(this, 'tipo-envio')"
                             data-group="tipo-envio"
                             class="shipping-option selected-option relative cursor-pointer rounded-xl p-6 border-2 border-primary bg-primary/5 transition-all duration-300 hover:border-primary">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-slate-900">Delivery Express (Guanare)</span>
                                <span class="font-black text-emerald-600 text-lg">Desde $4.99</span>
                            </div>
                            <p class="text-sm text-slate-500">Precio base estimado para paquetes de hasta 2kg.</p>
                            <div class="selected-dot absolute top-4 right-4 size-5 rounded-full border-[6px] border-primary transition-all duration-300"></div>
                        </div>

                        {{-- Opción Gratis --}}
                        <div onclick="selectShipping(this, 'tipo-envio')"
                             data-group="tipo-envio"
                             class="shipping-option relative cursor-pointer rounded-xl p-6 border-2 border-slate-100 bg-white transition-all duration-300 hover:border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">stars</span>
                                    <span class="font-bold text-slate-900">¡Promoción Envío Gratis!</span>
                                </div>
                                <span class="font-black text-emerald-600 text-lg">$0.00</span>
                            </div>
                            <p class="text-sm text-slate-500 font-medium">Aplicable a compras totales superiores a $50.00 dentro de Guanare.</p>
                            <div class="selected-dot absolute top-4 right-4 size-5 rounded-full border-2 border-slate-200 opacity-0 transition-all duration-300"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-px w-full bg-slate-100 mb-12"></div>

            {{-- Métodos Local --}}
            <div class="flex flex-col sm:flex-row gap-6">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-black text-slate-900 mb-3">Métodos de Entrega</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        Al confirmar tu compra, podrás elegir cómo recibir tu pedido:
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Delivery Express --}}
                        <div onclick="selectShipping(this, 'agencia')"
                             data-group="agencia"
                             class="shipping-option selected-option relative cursor-pointer rounded-xl h-20 flex flex-col items-center justify-center border-2 border-primary bg-primary/5 transition-all duration-300 hover:border-primary">
                            <span class="material-symbols-outlined text-primary mb-1">two_wheeler</span>
                            <span class="font-black text-primary tracking-widest text-xs uppercase">Delivery a Residencia</span>
                            <div class="selected-dot absolute top-3 right-3 size-4 rounded-full border-[5px] border-primary transition-all duration-300"></div>
                        </div>
                        {{-- Retiro en Tienda --}}
                        <div onclick="selectShipping(this, 'agencia')"
                             data-group="agencia"
                             class="shipping-option relative cursor-pointer rounded-xl h-20 flex flex-col items-center justify-center border-2 border-slate-100 bg-white transition-all duration-300 hover:border-slate-200">
                            <span class="material-symbols-outlined text-slate-400 mb-1 group-hover:text-primary">store</span>
                            <span class="font-black text-slate-400 tracking-widest text-xs uppercase group-hover:text-primary">Retiro en Tienda</span>
                            <div class="selected-dot absolute top-3 right-3 size-4 rounded-full border-2 border-slate-200 opacity-0 transition-all duration-300"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Confirm Button --}}
    <div class="text-center mt-4">
        <button onclick="confirmShipping()" class="bg-slate-900 hover:bg-black text-white font-black px-10 py-4 rounded-2xl tracking-widest text-sm uppercase transition-all active:scale-95 shadow-xl shadow-slate-900/20">
            Confirmar Selección
        </button>
    </div>
</div>

@push('scripts')
<script>
    function selectShipping(el, group) {
        // Deselect all in the same group
        document.querySelectorAll(`[data-group="${group}"]`).forEach(option => {
            option.classList.remove('selected-option', 'border-primary', 'bg-primary/5', 'text-primary');
            option.classList.add('border-slate-100', 'bg-white', 'text-slate-400');

            const dot = option.querySelector('.selected-dot');
            if (dot) {
                dot.classList.remove('border-[5px]', 'border-[6px]', 'border-primary');
                dot.classList.add('border-2', 'border-slate-200', 'opacity-0');
            }
        });

        // Select the clicked one
        el.classList.add('selected-option', 'border-primary', 'bg-primary/5', 'text-primary');
        el.classList.remove('border-slate-100', 'bg-white', 'text-slate-400');

        const dot = el.querySelector('.selected-dot');
        if (dot) {
            dot.classList.add('border-[5px]', 'border-primary');
            dot.classList.remove('border-2', 'border-slate-200', 'opacity-0');
        }
    }

    function confirmShipping() {
        const tipoEnvio  = document.querySelector('[data-group="tipo-envio"].selected-option');
        const agencia    = document.querySelector('[data-group="agencia"].selected-option');

        if (!tipoEnvio || !agencia) {
            Swal.fire({
                icon: 'warning',
                title: 'Faltan selecciones',
                text: 'Por favor elige el tipo de envío y la agencia antes de confirmar.',
                confirmButtonColor: '#8b52ff',
                confirmButtonText: 'Entendido',
                customClass: { popup: 'rounded-2xl', confirmButton: 'font-bold rounded-xl px-6 py-3 text-sm uppercase tracking-widest' }
            });
            return;
        }

        const tipoNombre  = tipoEnvio.querySelector('span.font-bold, span.font-black')?.textContent.trim() || 'Envío';
        const agenciaNombre = agencia.textContent.trim();

        Swal.fire({
            icon: 'success',
            title: '¡Configuración Guardada!',
            html: `
                <p class="text-slate-600 mb-2">Tu selección de envío ha sido registrada.</p>
                <div class="flex flex-col gap-2 mt-4 text-left">
                    <div class="flex items-center gap-3 bg-primary/5 rounded-xl px-4 py-3">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Tipo de Envío</p>
                            <p class="text-sm font-bold text-slate-900">${tipoNombre}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-primary/5 rounded-xl px-4 py-3">
                        <span class="material-symbols-outlined text-primary">handshake</span>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Agencia</p>
                            <p class="text-sm font-bold text-slate-900">${agenciaNombre}</p>
                        </div>
                    </div>
                </div>
            `,
            confirmButtonColor: '#8b52ff',
            confirmButtonText: '¡Perfecto!',
            customClass: { popup: 'rounded-2xl', confirmButton: 'font-bold rounded-xl px-6 py-3 text-sm uppercase tracking-widest' }
        });
    }
</script>
@endpush
@endsection
