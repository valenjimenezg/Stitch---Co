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
        <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Dirección de Entrega</h3>

            @if($direcciones->isNotEmpty())
                <div class="space-y-3">
                    <p class="text-sm text-slate-500">Mis direcciones guardadas:</p>
                    @foreach($direcciones as $dir)
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:border-primary/50 transition-colors">
                        <input type="radio" name="direccion_id" value="{{ $dir->id }}"
                               class="text-primary focus:ring-primary mr-4"/>
                        <span class="text-sm">{{ $dir->calle }}, {{ $dir->ciudad }}</span>
                    </label>
                    @endforeach
                    <div class="border-t border-slate-100 pt-4">
                        <label class="flex items-center gap-2 text-sm text-primary font-medium cursor-pointer">
                            <input type="radio" name="direccion_id" value="new" checked class="text-primary focus:ring-primary"/>
                            Nueva dirección
                        </label>
                    </div>
                </div>
            @endif

            <div class="space-y-4" id="nueva-direccion">
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700">Calle y Número *</label>
                    <input name="calle" type="text" value="{{ old('calle', auth()->user()->nombre . ' ' . auth()->user()->apellido) }}"
                           placeholder="Ej: Av. Circunvalación #123"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary" required/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700">Ciudad *</label>
                    <input name="ciudad" type="text" value="{{ old('ciudad', 'Cochabamba') }}"
                           placeholder="Cochabamba"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary" required/>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700">Referencia (opcional)</label>
                    <input name="referencia" type="text" value="{{ old('referencia') }}"
                           placeholder="Ej: Cerca al parque central"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                </div>
            </div>
        </div>

        {{-- Column 2: Payment --}}
        <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Método de Pago</h3>
            <div class="space-y-3" id="payment-methods">
                <label class="payment-option flex items-center p-4 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer transition-colors" data-target="efectivo-details">
                    <input checked name="metodo" type="radio" value="efectivo" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary icon">payments</span>
                        <span class="font-bold">Efectivo al recibir</span>
                    </div>
                </label>
                <label class="payment-option flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" data-target="pago-movil-details">
                    <input name="metodo" type="radio" value="pago_movil" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 icon">phone_iphone</span>
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
                <label class="payment-option flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" data-target="debito-details">
                    <input name="metodo" type="radio" value="debito_inmediato" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 icon">price_check</span>
                        <span class="font-bold text-slate-900">Débito Inmediato</span>
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

                <!-- Débito Inmediato Details -->
                <div id="debito-details" class="payment-details hidden space-y-4 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-inner">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 mb-4 shadow-sm text-sm">
                        <p class="font-bold text-slate-900 mb-2">Instrucciones de Débito:</p>
                        <p class="text-slate-500 text-xs">Por favor autoriza el débito en el portal de tu banco y proporciona el recibo de compra para validarlo en el sistema a nombre de Stitch & Co C.A.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor *</label>
                        <select name="banco_pago_debito" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary">
                            <option value="">Selecciona tu banco</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Provincial">Provincial</option>
                            <option value="Venezuela">Venezuela</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Número de Aprobación/Referencia *</label>
                        <input name="referencia_pago_debito" type="text" placeholder="Ej: 12345678" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                    </div>
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
                        else if (selectedMethod === 'debito_inmediato') targetId = 'debito-details';
                        
                        if (targetId) document.getElementById(targetId).classList.remove('hidden');

                        const selectedOption = document.querySelector(`input[value="${selectedMethod}"]`).closest('.payment-option');
                        selectedOption.classList.remove('border', 'border-slate-200');
                        selectedOption.classList.add('border-2', 'border-primary', 'bg-primary/5');
                        selectedOption.querySelector('.icon').classList.remove('text-slate-500');
                        selectedOption.querySelector('.icon').classList.add('text-primary');
                    }

                    radios.forEach(radio => radio.addEventListener('change', updatePaymentUI));
                    updatePaymentUI();
                });
            </script>

            {{-- Order summary --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-900 mb-4">Resumen</h4>
                
                <div id="checkout-summary-list"></div>
                
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-100">
                    <span class="text-slate-500">Total a pagar</span>
                    <span id="checkout-total-price" class="text-2xl font-black text-primary">Bs. 0.00</span>
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
        
        items.forEach(item => {
            const priceToUse = item.en_oferta ? item.precio_con_descuento : item.precio;
            const subtotal = priceToUse * item.cantidad;
            const html = `
                <div class="flex justify-between text-sm text-slate-600 mb-2">
                    <span>${item.nombre} × ${item.cantidad}</span>
                    <span>Bs. ${subtotal.toFixed(2)}</span>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', html);
        });

        const total = Cart.getTotal();
        document.getElementById('checkout-total-price').textContent = `Bs. ${total.toFixed(2)}`;
        
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
                    alert(error.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
@endpush
