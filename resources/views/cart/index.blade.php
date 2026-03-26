@extends('layouts.app')

@section('title', 'Tu Carrito — Stitch & Co')

@section('content')

{{-- Breadcrumbs --}}
<nav class="flex items-center gap-2 mb-8 text-sm font-medium">
    <a class="text-primary hover:underline" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-base text-slate-400">chevron_right</span>
    <span class="text-slate-900">Carrito de compras</span>
</nav>

<h1 class="text-3xl font-extrabold text-slate-900 mb-8 tracking-tight">Tu Carrito de Compras</h1>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div id="cart-container" class="hidden flex-col lg:flex-row gap-8">
    {{-- Cart Items --}}
    <div class="lg:w-[70%] space-y-4" id="cart-items-list">
        <!-- Rendered via JS -->
    </div>

    {{-- Order Summary --}}
    <div class="lg:w-[30%]">
        <div class="bg-white border border-primary/20 rounded-xl p-6 shadow-xl sticky top-24">
            <h2 class="text-xl font-bold mb-6 text-slate-900">Resumen del pedido</h2>
            <div class="space-y-4 mb-6">
                <div class="flex justify-between text-slate-600">
                    <span id="cart-subtotal-items">Subtotal (0 artículos)</span>
                    <span id="cart-subtotal-price">Bs. 0.00</span>
                </div>
                <div class="pt-4 border-t border-primary/10 flex justify-between items-center">
                    <span class="text-lg font-bold text-slate-900">Total</span>
                    <span id="cart-total-price" class="text-2xl font-black text-primary">Bs. 0.00</span>
                </div>
            </div>
            
            @auth
                <a href="{{ route('checkout.index') }}"
                   class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                    Proceder al pago
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            @else
                <a href="{{ route('checkout.guest') }}"
                   class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                    Proceder al pago
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            @endauth

            <div class="mt-6 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="material-symbols-outlined text-green-500 text-sm">verified_user</span>
                    Pago 100% seguro y encriptado
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="material-symbols-outlined text-primary/60 text-sm">local_shipping</span>
                    Entrega a domicilio disponible
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Empty cart --}}
<div id="cart-empty-state" class="text-center py-24 hidden">
    <span class="material-symbols-outlined text-7xl text-slate-300 mb-4 block">shopping_cart</span>
    <h2 class="text-2xl font-bold text-slate-600 mb-2">Tu carrito está vacío</h2>
    <p class="text-slate-400 mb-8">Agrega productos para continuar con tu compra.</p>
    <a href="{{ route('home') }}" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all">
        Ir a la tienda
    </a>
</div>

@endsection

@push('scripts')
<script>
    function renderCart() {
        const items = Cart.getItems();
        const container = document.getElementById('cart-container');
        const emptyState = document.getElementById('cart-empty-state');
        const list = document.getElementById('cart-items-list');
        
        if(items.length === 0) {
            container.classList.add('hidden');
            container.classList.remove('flex');
            emptyState.classList.remove('hidden');
            return;
        }

        container.classList.remove('hidden');
        container.classList.add('flex');
        emptyState.classList.add('hidden');
        
        list.innerHTML = '';
        let totalQty = 0;
        
        items.forEach(item => {
            totalQty += item.cantidad;
            const priceToUse = item.en_oferta ? item.precio_con_descuento : item.precio;
            const subtotal = priceToUse * item.cantidad;
            
            const meta = [];
            if(item.color) meta.push('Color: ' + item.color);
            if(item.grosor) meta.push('Grosor: ' + item.grosor);
            if(item.marca) meta.push(item.marca);
            
            const imgHtml = item.imagen 
                ? `<img src="${item.imagen}" class="w-full h-full object-cover"/>`
                : `<div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-primary/30 text-3xl">straighten</span></div>`;

            const itemHtml = `
            <div class="bg-white border border-primary/10 rounded-xl p-4 flex gap-4 items-center shadow-sm">
                <div class="h-24 w-24 bg-primary/5 rounded-lg flex-shrink-0 overflow-hidden border border-primary/10">
                    ${imgHtml}
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold text-slate-900">${item.nombre}</h3>
                    <p class="text-xs text-slate-500 mb-2">${meta.join(' • ')}</p>
                    <span class="text-sm font-semibold text-primary">Bs. ${parseFloat(priceToUse).toFixed(2)}</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-primary/20 rounded-lg overflow-hidden">
                        <button onclick="Cart.updateQty(${item.id}, -1)" class="px-3 py-2 hover:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-sm">remove</span>
                        </button>
                        <span class="px-3 text-sm font-bold">${item.cantidad}</span>
                        <button onclick="Cart.updateQty(${item.id}, 1)" class="px-3 py-2 hover:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                    </div>
                    <div class="w-24 text-right">
                        <p class="text-sm font-bold text-slate-900">Bs. ${subtotal.toFixed(2)}</p>
                    </div>
                    <button onclick="Cart.remove(${item.id})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>`;
            list.insertAdjacentHTML('beforeend', itemHtml);
        });

        const total = Cart.getTotal();
        document.getElementById('cart-subtotal-items').textContent = `Subtotal (${totalQty} artículos)`;
        document.getElementById('cart-subtotal-price').textContent = `Bs. ${total.toFixed(2)}`;
        document.getElementById('cart-total-price').textContent = `Bs. ${total.toFixed(2)}`;
    }

    window.addEventListener('cartUpdated', renderCart);
    document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endpush
