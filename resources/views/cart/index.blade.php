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
                    <span class="text-lg font-bold text-slate-900">Total a Pagar</span>
                    <div class="text-right">
                        <span id="cart-total-price" class="text-2xl font-black text-primary block">Bs. 0.00</span>
                        <span id="cart-total-ref" class="text-xs font-bold text-slate-400 block mt-0.5">Ref: $0.00</span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('checkout.init') }}"
               class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                Proceder al pago
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>

            <div class="mt-6 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="material-symbols-outlined text-green-500 text-sm">verified_user</span>
                    Pago 100% seguro y encriptado
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="material-symbols-outlined text-primary/60 text-sm">local_shipping</span>
                    Entrega a domicilio disponible
                </div>
                <div class="flex items-start gap-2 text-xs text-amber-600 bg-amber-50 p-2.5 rounded-lg border border-amber-100 mt-2">
                    <span class="material-symbols-outlined text-amber-500 text-sm mt-0.5">info</span>
                    <p>Total calculado a la Tasa BCV Oficial de hoy: <br><strong class="font-black tracking-widest text-amber-700">Bs. {{ number_format(bcv_rate(), 2, ',', '.') }}</strong></p>
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
        const bcvRate = {{ bcv_rate() }};
        
        items.forEach(item => {
            totalQty += item.cantidad;
            const priceToUse = parseFloat(item.en_oferta ? item.precio_con_descuento : item.precio);
            const subtotalUsd = priceToUse * item.cantidad;
            
            const itemPriceBs = priceToUse * bcvRate;
            const subtotalBs = subtotalUsd * bcvRate;
            
            const meta = [];
            if(item.color) meta.push('Color: ' + item.color);
            if(item.grosor) meta.push('Grosor: ' + item.grosor);
            if(item.marca) meta.push(item.marca);
            
            const imgHtml = item.imagen 
                ? `<img src="${item.imagen}" class="w-full h-full object-cover"/>`
                : `<div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-primary/30 text-3xl">straighten</span></div>`;

            const itemHtml = `
            <div class="bg-white border border-primary/10 rounded-xl p-4 flex flex-col md:flex-row md:items-center gap-4 shadow-sm relative">
                <div class="flex gap-4 items-center w-full md:w-auto overflow-hidden">
                    <a href="/producto/${item.id}" class="h-20 w-20 md:h-24 md:w-24 bg-primary/5 rounded-lg flex-shrink-0 overflow-hidden border border-primary/10 block hover:border-primary/50 transition-colors" title="Ver producto de nuevo">
                        ${imgHtml}
                    </a>
                    <div class="flex-grow pr-8 md:pr-0 overflow-hidden">
                        <h3 class="font-bold text-slate-900 leading-tight text-sm md:text-base break-words"><a href="/producto/${item.id}" class="hover:text-primary hover:underline transition-colors">${item.nombre}</a></h3>
                        <p class="text-[11px] md:text-xs text-slate-500 mb-1.5 truncate">${meta.join(' • ')}</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] md:text-sm font-black text-primary whitespace-nowrap">Bs. ${itemPriceBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>
                    </div>
                    
                    <!-- Delete button (Mobile Absolute Top Right) -->
                    <button onclick="Cart.remove('${item.cartItemId || item.id}')" class="absolute top-4 right-4 md:hidden p-1.5 text-slate-400 bg-slate-50 rounded-lg border border-slate-100 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition-colors flex items-center shrink-0">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
                
                <div class="flex items-center justify-between md:justify-end gap-2 md:gap-4 flex-grow md:flex-grow-0 pt-3 md:pt-0 border-t border-slate-100 md:border-transparent md:border-t-0 w-full md:w-auto">
                    <div class="flex items-center border border-primary/20 rounded-lg overflow-hidden bg-white shadow-sm h-8 md:h-10">
                        <button onclick="Cart.updateQty('${item.cartItemId || item.id}', -1)" class="px-3 h-full hover:bg-primary/5 text-slate-600 transition-colors flex items-center">
                            <span class="material-symbols-outlined text-[16px] md:text-sm">remove</span>
                        </button>
                        <span class="px-2 md:px-3 text-xs md:text-sm font-bold text-slate-900">${item.cantidad}</span>
                        <button onclick="Cart.updateQty('${item.cartItemId || item.id}', 1)" class="px-3 h-full hover:bg-primary/5 text-slate-600 transition-colors flex items-center">
                            <span class="material-symbols-outlined text-[16px] md:text-sm">add</span>
                        </button>
                    </div>
                    <div class="text-right flex flex-col items-end flex-shrink-0 md:w-28">
                        <p class="text-sm md:text-[15px] font-black text-slate-900 tracking-tight leading-none mb-1">Bs. ${subtotalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 bg-slate-50 px-1 py-0.5 rounded">Ref: $${subtotalUsd.toFixed(2)}</p>
                    </div>
                    
                    <!-- Delete button (Desktop) -->
                    <button onclick="Cart.remove('${item.cartItemId || item.id}')" class="hidden md:flex p-2.5 ml-1 text-slate-400 bg-slate-50 rounded-xl border border-slate-100 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition-colors items-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>`;
            list.insertAdjacentHTML('beforeend', itemHtml);
        });

        const totalUsd = Cart.getTotal();
        const totalBs = totalUsd * bcvRate;
        
        document.getElementById('cart-subtotal-items').textContent = `Subtotal (${totalQty} artículos)`;
        document.getElementById('cart-subtotal-price').textContent = `Bs. ${totalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('cart-total-price').textContent = `Bs. ${totalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('cart-total-ref').textContent = `Ref: $${totalUsd.toFixed(2)}`;
    }

    window.addEventListener('cartUpdated', renderCart);
    document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endpush
