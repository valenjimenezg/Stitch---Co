@extends('layouts.app')

@section('title', 'Checkout — Stitch & Co')

@section('content')

<div class="flex items-center gap-3 mb-8">
    <a href="{{ route('cart.index') }}" class="text-slate-400 hover:text-primary">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
        <span class="h-8 w-8 bg-primary/20 text-primary rounded-full flex items-center justify-center text-sm font-bold">1</span>
        Información de Pago y Envío
    </h1>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
    @csrf
    <input type="hidden" name="cart_payload" id="cart_payload" value="">
    <div class="grid lg:grid-cols-2 gap-12">

        {{-- Column 1: Shipping --}}
        <div class="space-y-8">
            


            {{-- Sección de Modalidad --}}
            <section>
                <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-3">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                    </div>
                    <h3 class="text-[15px] font-black uppercase tracking-wider text-slate-800">Método de Entrega</h3>
                </div>

                {{-- Banner de Zona Exclusiva --}}
                <div class="bg-gradient-to-r from-amber-50 to-orange-50/30 border border-amber-200/50 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-6">
                    <div class="bg-white rounded-full p-1.5 shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-amber-500 fill-current block text-lg">location_on</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-amber-900 text-sm mb-0.5">Operamos solo en Guanare</h4>
                        <p class="text-xs text-amber-800/80 font-medium leading-relaxed">Nuestros despachos (Delivery o Retiro) están limitados por el momento exclusivamente al casco urbano de la ciudad de Guanare, Portuguesa.</p>
                    </div>
                </div>

                {{-- Opciones de Entrega Tipo Tarjeta Premium --}}
                <div class="grid grid-cols-2 gap-4" id="shipping-methods">
                    <label class="shipping-option relative flex flex-col items-center justify-center p-6 border-2 border-primary bg-primary/5 rounded-2xl cursor-pointer transition-all group hover:border-primary shadow-sm hover:shadow-md">
                        <input checked name="tipo_envio" type="radio" value="delivery" class="sr-only peer"/>
                        <span class="material-symbols-outlined text-primary text-4xl mb-3 icon transition-transform group-hover:-translate-y-1">two_wheeler</span>
                        <span class="font-black text-slate-900 text-sm block mb-1">Delivery</span>
                        <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider">A domicilio</span>
                        
                        <div class="check-indicator absolute top-3 right-3 size-5 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white transition-opacity">
                            <span class="material-symbols-outlined text-[12px] font-bold">check</span>
                        </div>
                    </label>

                    <label class="shipping-option relative flex flex-col items-center justify-center p-6 border-2 border-slate-100 bg-white rounded-2xl cursor-pointer transition-all group hover:border-primary/50 shadow-sm hover:shadow-md">
                        <input name="tipo_envio" type="radio" value="retiro_tienda" class="sr-only peer"/>
                        <span class="material-symbols-outlined text-slate-400 text-4xl mb-3 icon transition-transform group-hover:-translate-y-1">storefront</span>
                        <span class="font-black text-slate-900 text-sm block mb-1">Retiro</span>
                        <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider">En tienda física</span>
                        
                        <div class="check-indicator absolute top-3 right-3 size-5 rounded-full border-2 border-slate-200 flex items-center justify-center text-transparent transition-opacity">
                            <span class="material-symbols-outlined text-[12px] font-bold opacity-0">check</span>
                        </div>
                    </label>
                </div>
            </section>

            {{-- Sección de Dirección --}}
            <section id="direccion-container" class="transition-all duration-300">
                <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-3">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-sm">home_pin</span>
                    </div>
                    <h3 class="text-[15px] font-black uppercase tracking-wider text-slate-800">Dirección de Entrega</h3>
                </div>

                @if($direcciones->isNotEmpty())
                    <div class="space-y-3 mb-6">
                        <p class="text-[13px] font-bold text-slate-600 uppercase tracking-wide">Mis direcciones guardadas</p>
                        @foreach($direcciones as $dir)
                        <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary/50 transition-colors bg-white shadow-sm">
                            <input type="radio" name="direccion_id" value="{{ $dir->id }}"
                                   class="text-primary focus:ring-primary w-4 h-4 mr-4"/>
                            <span class="text-sm font-semibold text-slate-700">{{ $dir->calle }}, {{ $dir->ciudad }}</span>
                        </label>
                        @endforeach
                        <div class="pt-2">
                            <label class="inline-flex items-center gap-2 text-sm text-primary font-bold cursor-pointer hover:text-primary-dark transition-colors">
                                <input type="radio" name="direccion_id" value="new" checked class="text-primary focus:ring-primary w-4 h-4"/>
                                Usar una dirección nueva
                            </label>
                        </div>
                    </div>
                @endif

                <div class="space-y-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm" id="nueva-direccion">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Ciudad de Destino *</label>
                        <div class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3.5 flex items-center justify-between cursor-not-allowed select-none">
                            <div class="flex items-center gap-2.5 text-slate-500">
                                <span class="material-symbols-outlined text-lg">location_city</span>
                                <span class="font-bold text-sm">Guanare, Portuguesa</span>
                            </div>
                            <span class="bg-slate-200 text-slate-500 text-[10px] px-2 py-0.5 rounded-md font-black uppercase tracking-wider">FIJO</span>
                        </div>
                        <!-- Input real oculto -->
                        <input type="hidden" name="ciudad" value="Guanare" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Calle / Urb. / Sector / Número *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">signpost</span>
                            </div>
                            <input name="calle" type="text" value="{{ old('calle') }}"
                                   placeholder="Ej: Barrio Sucre, Carrera 5, Casa #12"
                                   class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm" required/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Punto de Referencia <span class="text-slate-400 font-normal">(Opcional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">share_location</span>
                            </div>
                            <input name="referencia" type="text" value="{{ old('referencia') }}"
                                   placeholder="Ej: Portón negro frente a la panadería..."
                                   class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Column 2: Payment --}}
        <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Método de Pago</h3>
            <div class="space-y-3" id="payment-methods">
                <label class="payment-option flex items-center p-4 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer transition-colors" data-target="pago-movil-details">
                    <input checked name="metodo" type="radio" value="pago_movil" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary icon">phone_iphone</span>
                        <span class="font-bold text-slate-900">Pago Móvil</span>
                    </div>
                </label>
                <label class="payment-option flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" data-target="transferencia-details">
                    <input name="metodo" type="radio" value="transferencia_p2p" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 icon">account_balance</span>
                        <span class="font-bold text-slate-900">Transferencia P2P</span>
                    </div>
                </label>
            </div>

            <!-- Formularios desplegables -->
            <div class="mt-4">
                <!-- Pago Móvil Details -->
                <div id="pago-movil-details" class="payment-details hidden space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-inner">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 mb-4 shadow-sm text-sm">
                        <p class="font-bold text-slate-900 mb-2">Realiza tu pago a:</p>
                        <ul class="space-y-1 text-slate-500">
                            <li><strong>Banco:</strong> Banesco (0134)</li>
                            <li><strong>Teléfono:</strong> 0424-5659154</li>
                            <li><strong>RIF:</strong> J-12345678-9</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor *</label>
                        <select name="banco_pago" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary">
                            <option value="">Selecciona tu banco</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Provincial">Provincial</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="BNC">BNC</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Teléfono Emisor</label>
                            <input name="telefono_pago" type="text" placeholder="Ej: 0414..." class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Ref. (Últimos 4/6) *</label>
                            <input name="referencia_pago" type="text" placeholder="Ej: 1234" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                        </div>
                    </div>
                </div>

                <!-- Transferencia Details -->
                <div id="transferencia-details" class="payment-details hidden space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-inner">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 mb-4 shadow-sm text-sm">
                        <p class="font-bold text-slate-900 mb-2">Datos para transferencia:</p>
                        <ul class="space-y-1 text-slate-500">
                            <li><strong>Banco:</strong> Banesco</li>
                            <li><strong>Cuenta:</strong> 0134-0123-12-1234567890</li>
                            <li><strong>Titular:</strong> Stitch & Co C.A.</li>
                            <li><strong>RIF:</strong> J-12345678-9</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor *</label>
                        <select name="banco_pago_transf" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary">
                            <option value="">Selecciona tu banco</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Provincial">Provincial</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Número de Referencia Completo *</label>
                        <input name="referencia_pago_transf" type="text" placeholder="Ej: 908172931" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                    </div>
                </div>

                <!-- Input Componente de Comprobante (Universal para pagos offline) -->
                <div id="comprobante-upload-details" class="payment-details hidden space-y-3 bg-white p-5 rounded-xl border-2 border-dashed border-slate-300">
                    <label class="block text-sm font-bold text-slate-800 text-center mb-1">Adjuntar Comprobante (Obligatorio)</label>
                    <p class="text-xs text-slate-500 text-center mb-3">Sube la captura de pantalla de la transferencia o pago móvil (JPG, PNG, PDF)</p>
                    <input type="file" name="comprobante" accept="image/*,.pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"/>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const radios = document.querySelectorAll('input[name="metodo"]');
                    const detailsBlocks = document.querySelectorAll('.payment-details');
                    
                    function updatePaymentUI() {
                        const selectedMethod = document.querySelector('input[name="metodo"]:checked').value;
                        
                        detailsBlocks.forEach(block => block.classList.add('hidden'));
                        document.querySelectorAll('.payment-option').forEach(option => {
                            option.classList.remove('border-2', 'border-primary', 'bg-primary/5');
                            option.classList.add('border', 'border-slate-200');
                            option.querySelector('.icon').classList.remove('text-primary');
                            option.querySelector('.icon').classList.add('text-slate-500');
                        });

                        let targetId = '';
                        if (selectedMethod === 'pago_movil') targetId = 'pago-movil-details';
                        else if (selectedMethod === 'transferencia_p2p') targetId = 'transferencia-details';
                        
                        if (targetId) document.getElementById(targetId).classList.remove('hidden');
                        
                        // Show file upload requirement for both
                        if (selectedMethod === 'pago_movil' || selectedMethod === 'transferencia_p2p') {
                            document.getElementById('comprobante-upload-details').classList.remove('hidden');
                        }

                        const selectedOption = document.querySelector(`input[value="${selectedMethod}"]`).closest('.payment-option');
                        selectedOption.classList.remove('border', 'border-slate-200');
                        selectedOption.classList.add('border-2', 'border-primary', 'bg-primary/5');
                        selectedOption.querySelector('.icon').classList.remove('text-slate-500');
                        selectedOption.querySelector('.icon').classList.add('text-primary');
                    }

                    radios.forEach(radio => radio.addEventListener('change', updatePaymentUI));
                    updatePaymentUI();

                    // MÉTODOS DE ENVÍO UI
                    const shippingRadios = document.querySelectorAll('input[name="tipo_envio"]');
                    const direccionContainer = document.getElementById('direccion-container');

                    function updateShippingUI() {
                        const selectedMethod = document.querySelector('input[name="tipo_envio"]:checked').value;
                        
                        document.querySelectorAll('.shipping-option').forEach(option => {
                            option.classList.remove('border-primary', 'bg-primary/5');
                            option.classList.add('border-slate-100', 'bg-white');
                            option.querySelector('.icon').classList.remove('text-primary');
                            option.querySelector('.icon').classList.add('text-slate-400');
                            
                            const indicator = option.querySelector('.check-indicator');
                            if(indicator) {
                                indicator.classList.remove('border-primary', 'bg-primary');
                                indicator.classList.add('border-slate-200');
                                indicator.querySelector('span').classList.remove('opacity-100');
                                indicator.querySelector('span').classList.add('opacity-0');
                            }
                        });

                        const selectedOption = document.querySelector(`input[value="${selectedMethod}"]`).closest('.shipping-option');
                        selectedOption.classList.remove('border-slate-100', 'bg-white');
                        selectedOption.classList.add('border-primary', 'bg-primary/5');
                        selectedOption.querySelector('.icon').classList.remove('text-slate-400');
                        selectedOption.querySelector('.icon').classList.add('text-primary');

                        const activeIndicator = selectedOption.querySelector('.check-indicator');
                        if (activeIndicator) {
                            activeIndicator.classList.remove('border-slate-200');
                            activeIndicator.classList.add('border-primary', 'bg-primary');
                            activeIndicator.querySelector('span').classList.remove('opacity-0');
                            activeIndicator.querySelector('span').classList.add('opacity-100');
                        }

                        // Mostrar u ocular Dirección de envío
                        if (selectedMethod === 'retiro_tienda') {
                            direccionContainer.classList.add('hidden');
                            document.querySelector('input[name="calle"]').removeAttribute('required');
                        } else {
                            direccionContainer.classList.remove('hidden');
                            document.querySelector('input[name="calle"]').setAttribute('required', 'required');
                        }
                    }

                    shippingRadios.forEach(radio => radio.addEventListener('change', () => {
                        updateShippingUI();
                        renderCheckoutCart(); // Trigger dynamic recalculation of delivery fee
                    }));
                    updateShippingUI();

                });
            </script>

            {{-- Order summary --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-900 mb-4">Resumen</h4>
                
                <div id="checkout-summary-list"></div>
                
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-100">
                    <span class="text-slate-500 font-bold">Total a pagar</span>
                    <div id="checkout-total-price" class="text-right flex flex-col items-end">
                        <span class="text-2xl font-black text-primary">Bs. 0.00</span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-amber-50 border border-amber-100/60 rounded-xl flex gap-3 text-xs text-amber-700">
                    <span class="material-symbols-outlined text-amber-500 text-lg mt-0.5">info</span>
                    <p>Total calculado a la tasa BCV oficial: <strong class="font-black">Bs. {{ number_format(bcv_rate(), 2, ',', '.') }}</strong><br>Esta tasa quedará registrada en su factura al momento de presionar Finalizar Compra.</p>
                </div>
                <button type="submit"
                        class="w-full py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95 mt-6">
                    Finalizar compra
                </button>
                
                <button type="button" onclick="cancelTransaction()" class="w-full py-4 mt-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition-all">
                    Cancelar Compra
                </button>

                <p class="text-[10px] text-center text-slate-400 mt-4">
                    Al hacer clic, aceptas nuestros <a class="underline" href="#">Términos y Condiciones</a>
                </p>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function renderCheckoutCart() {
        if (typeof Cart === 'undefined') {
            setTimeout(renderCheckoutCart, 100);
            return;
        }
        
        const items = Cart.getItems();
        if (items.length === 0) {
            window.location.href = '/carrito';
            return;
        }

        const list = document.getElementById('checkout-summary-list');
        list.innerHTML = '';
        
        const bcvRate = {{ bcv_rate() }};

        items.forEach(item => {
            const priceToUse = parseFloat(item.en_oferta ? item.precio_con_descuento : item.precio);
            const subtotalUsd = priceToUse * item.cantidad;
            const subtotalBs = subtotalUsd * bcvRate;
            
            const html = `
                <div class="flex justify-between text-sm text-slate-600 mb-3">
                    <span class="flex-1 pr-4"><a href="/producto/${item.id}" class="hover:text-primary hover:underline transition-colors block" title="Ver producto">${item.nombre}</a> <span class="text-xs text-slate-400">× ${item.cantidad}</span></span>
                    <div class="text-right">
                        <span class="block font-bold text-slate-800 tracking-tight">Bs. ${subtotalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        <span class="text-[10px] text-slate-400 font-bold">Ref: $${subtotalUsd.toFixed(2)}</span>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', html);
        });

        const subtotalUsd = Cart.getTotal();
        const subtotalBs = subtotalUsd * bcvRate;
        
        const isDelivery = document.querySelector('input[name="tipo_envio"]:checked')?.value === 'delivery';
        const deliveryUsd = isDelivery ? 1.00 : 0.00;
        const deliveryBs = deliveryUsd * bcvRate;

        const ivaUsd = subtotalUsd * 0.16;
        const ivaBs = ivaUsd * bcvRate;

        const totalUsd = subtotalUsd + ivaUsd + deliveryUsd;
        const totalBs = subtotalBs + ivaBs + deliveryBs;
        
        document.getElementById('checkout-total-price').innerHTML = `
            <div class="text-right w-full pt-2">
                <div class="flex justify-between text-xs text-slate-500 mb-1 w-full gap-8">
                    <span>Subtotal:</span>
                    <span>$${subtotalUsd.toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mb-1 w-full gap-8">
                    <span>IVA (16%):</span>
                    <span>$${ivaUsd.toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-xs text-slate-500 border-b border-slate-100 pb-2 mb-2 w-full gap-8" style="display: ${isDelivery ? 'flex' : 'none'};">
                    <span>Delivery:</span>
                    <span>$${deliveryUsd.toFixed(2)}</span>
                </div>
                <div class="flex flex-col items-end pt-1">
                    <span class="block text-2xl font-black text-primary">Bs. ${totalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    <span class="block text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-wide">Ref: $${totalUsd.toFixed(2)}</span>
                </div>
            </div>
        `;
        
        // Populate Hidden Input
        document.getElementById('cart_payload').value = JSON.stringify(items);
    }

    function cancelTransaction() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Cancelar compra?',
                text: '¿Estás seguro que deseas cancelar y vaciar tu carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Volver',
                customClass: {
                    title: 'text-xl font-bold text-slate-800',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg font-bold px-6 outline-none',
                    cancelButton: 'rounded-lg font-bold px-6 outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof Cart !== 'undefined') Cart.clear();
                    else localStorage.removeItem('stitch_cart');
                    window.location.href = '/';
                }
            });
        } else {
            if(confirm('¿Estás seguro que deseas cancelar y vaciar tu carrito?')) {
                if (typeof Cart !== 'undefined') Cart.clear();
                else localStorage.removeItem('stitch_cart');
                window.location.href = '/';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCheckoutCart();

        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Validando Stock...';
                submitBtn.disabled = true;

                // Sync the latest payload explicitly before submit
                const items = typeof Cart !== 'undefined' ? Cart.getItems() : JSON.parse(localStorage.getItem('stitch_cart') || '[]');
                document.getElementById('cart_payload').value = JSON.stringify(items);

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        // Laravel validation errors (422) or our custom throw
                        throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Error en la validación'));
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        if (typeof Cart !== 'undefined') Cart.clear();
                        else localStorage.removeItem('stitch_cart');
                        
                        window.location.href = data.redirect || '/';
                    } else {
                        throw new Error(data.message || 'Error desconocido.');
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Pago No Procesado',
                            text: error.message,
                            icon: 'error',
                            confirmButtonColor: '#334155',
                            confirmButtonText: 'Aceptar',
                            customClass: {
                                title: 'text-xl font-bold text-slate-800',
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-lg font-bold px-8 outline-none bg-slate-800 hover:bg-slate-900'
                            }
                        });
                    } else {
                        alert(error.message);
                    }
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
@endpush
