@extends('layouts.app')
@section('title', 'Tu Carrito — Stitch & Co')

@push('styles')
<style>
/* ── Cart Layout ─────────────────────────────────────── */
.cart-page-title {
    font-size: 2rem; font-weight: 900; color: #1e1b4b;
    letter-spacing: -.5px; margin-bottom: 6px;
    display: flex; align-items: center; gap: 10px;
}
.cart-page-title span { color: #7c3aed; }

/* ── Cart Item Card ──────────────────────────────────── */
.cart-item-card {
    background: #fff;
    border: 1.5px solid #f0ebff;
    border-radius: 20px;
    padding: 16px 20px;
    display: flex; flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, border-color .2s;
}
.cart-item-card:hover {
    box-shadow: 0 8px 30px -8px rgba(109,40,217,0.12);
    border-color: #ddd6fe;
}

/* Imagen */
.cart-item-img {
    width: 80px; height: 80px; border-radius: 14px;
    overflow: hidden; flex-shrink: 0;
    background: #f8f7ff;
    border: 1.5px solid #ede9fe;
}
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; }

/* Nombre + meta */
.cart-item-name {
    font-size: 14px; font-weight: 700; color: #1e1b4b;
    line-height: 1.3; text-decoration: none;
    transition: color .15s;
}
.cart-item-name:hover { color: #7c3aed; }
.cart-item-meta { font-size: 11px; color: #9ca3af; font-weight: 500; margin-top: 3px; }
.cart-item-unit-price {
    font-size: 12px; font-weight: 700; color: #7c3aed;
    background: #f3f0ff; padding: 2px 8px; border-radius: 50px;
    display: inline-block; margin-top: 5px;
}

/* Qty stepper */
.qty-stepper {
    display: inline-flex; align-items: center;
    border: 1.5px solid #ede9fe; border-radius: 50px;
    overflow: hidden; background: #faf8ff;
    height: 36px;
}
.qty-btn {
    width: 36px; height: 100%;
    border: none; background: transparent;
    cursor: pointer; color: #6b7280;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}
.qty-btn:hover { background: #f3f0ff; color: #7c3aed; }
.qty-value {
    min-width: 32px; text-align: center;
    font-size: 13px; font-weight: 800; color: #1e1b4b;
}

/* Subtotal item */
.cart-item-subtotal {
    font-size: 15px; font-weight: 900; color: #1e1b4b; white-space: nowrap;
}
.cart-item-ref {
    font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase;
}

/* Delete btn */
.cart-delete-btn {
    width: 34px; height: 34px; border-radius: 10px;
    border: 1.5px solid #fee2e2; background: #fff5f5;
    color: #f87171; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s, border-color .15s;
    flex-shrink: 0;
}
.cart-delete-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }

/* ── Order Summary ───────────────────────────────────── */
.order-summary {
    background: #fff;
    border: 1.5px solid #ede9fe;
    border-radius: 24px;
    padding: 24px;
    position: sticky; top: 88px;
    box-shadow: 0 8px 40px -12px rgba(109,40,217,0.15);
}
.order-summary-title {
    font-size: 1.05rem; font-weight: 800; color: #1e1b4b;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
.summary-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 13px; color: #6b7280; padding: 8px 0;
}
.summary-divider { border: none; border-top: 1px solid #f0ebff; margin: 8px 0; }
.summary-total-label { font-size: 15px; font-weight: 800; color: #1e1b4b; }
.summary-total-price {
    font-size: 1.6rem; font-weight: 900; color: #7c3aed;
    letter-spacing: -.5px; line-height: 1;
}
.summary-total-ref { font-size: 11px; font-weight: 600; color: #9ca3af; margin-top: 2px; }

/* Checkout button */
.checkout-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 14px 20px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; font-size: 15px; font-weight: 800;
    border-radius: 16px; border: none; cursor: pointer;
    text-decoration: none;
    box-shadow: 0 8px 24px -6px rgba(109,40,217,0.45);
    transition: transform .2s, box-shadow .2s;
    margin-top: 20px;
}
.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -6px rgba(109,40,217,0.55);
    color: #fff;
}
.checkout-btn:active { transform: translateY(0); }

/* Trust badges */
.trust-badges { margin-top: 18px; display: flex; flex-direction: column; gap: 8px; }
.trust-badge {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 600; color: #6b7280;
}
.trust-badge .material-symbols-outlined { font-size: 16px; }

/* BCV notice */
.bcv-notice {
    display: flex; align-items: flex-start; gap: 8px;
    background: #fffbeb; border: 1px solid #fde68a;
    border-radius: 12px; padding: 10px 12px;
    margin-top: 12px;
    font-size: 11px; font-weight: 600; color: #92400e; line-height: 1.4;
}
.bcv-notice .material-symbols-outlined { font-size: 15px; color: #f59e0b; flex-shrink: 0; margin-top: 1px; }

/* ── Empty State ─────────────────────────────────────── */
.cart-empty {
    text-align: center; padding: 80px 20px;
}
.cart-empty-icon {
    width: 96px; height: 96px; border-radius: 50%;
    background: linear-gradient(135deg, #f3f0ff, #ede9fe);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
}

@media (min-width: 768px) {
    .cart-item-card { flex-direction: row; align-items: center; }
    .cart-item-img { width: 88px; height: 88px; }
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 mb-6 text-sm font-medium">
    <a class="text-primary hover:underline" href="{{ route('home') }}">Inicio</a>
    <span class="material-symbols-outlined text-base text-slate-300">chevron_right</span>
    <span class="text-slate-500">Carrito de compras</span>
</nav>

{{-- Page Title --}}
<div class="cart-page-title mb-8">
    <span class="material-symbols-outlined" style="font-size:28px; color:#7c3aed; font-variation-settings:'FILL' 1;">shopping_bag</span>
    Tu Carrito de Compras
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
        <span class="material-symbols-outlined text-[18px]">error</span>
        {{ session('error') }}
    </div>
@endif

{{-- Cart Layout --}}
<div id="cart-container" class="hidden flex-col lg:flex-row gap-8">

    {{-- Items --}}
    <div class="lg:w-[65%] space-y-4" id="cart-items-list">
        {{-- Rendered via JS --}}
    </div>

    {{-- Order Summary --}}
    <div class="lg:w-[35%]">
        <div class="order-summary">

            <div class="order-summary-title">
                <span class="material-symbols-outlined" style="font-size:20px; color:#7c3aed;">receipt_long</span>
                Resumen del pedido
            </div>

            <div class="summary-row">
                <span id="cart-subtotal-items" class="text-slate-500">Subtotal (0 artículos)</span>
                <span id="cart-subtotal-price" class="font-bold text-slate-700">Bs. 0,00</span>
            </div>

            <hr class="summary-divider">

            <div class="flex justify-between items-end mt-2">
                <span class="summary-total-label">Total a Pagar</span>
                <div class="text-right">
                    <div id="cart-total-price" class="summary-total-price">Bs. 0,00</div>
                    <div id="cart-total-ref" class="summary-total-ref">Ref: $0.00</div>
                </div>
            </div>

            <a href="{{ route('checkout.init') }}" class="checkout-btn">
                Proceder al pago
                <span class="material-symbols-outlined" style="font-size:20px;">arrow_forward</span>
            </a>

            <div class="trust-badges">
                <div class="trust-badge">
                    <span class="material-symbols-outlined" style="color:#10b981;">verified_user</span>
                    Pago 100% seguro y encriptado
                </div>
                <div class="trust-badge">
                    <span class="material-symbols-outlined" style="color:#7c3aed;">local_shipping</span>
                    Entrega a domicilio disponible
                </div>
            </div>

            <div class="bcv-notice">
                <span class="material-symbols-outlined">info</span>
                <span>Total calculado a la Tasa BCV Oficial de hoy: <strong style="color:#78350f;">Bs. {{ number_format(bcv_rate(), 2, ',', '.') }}</strong></span>
            </div>

        </div>
    </div>
</div>

{{-- Empty State --}}
<div id="cart-empty-state" class="cart-empty hidden">
    <div class="cart-empty-icon">
        <span class="material-symbols-outlined" style="font-size:44px; color:#a78bfa; font-variation-settings:'FILL' 1;">shopping_bag</span>
    </div>
    <h2 style="font-size:1.4rem; font-weight:800; color:#1e1b4b; margin-bottom:8px;">Tu carrito está vacío</h2>
    <p style="color:#9ca3af; font-size:14px; margin-bottom:28px;">Agrega productos para continuar con tu compra.</p>
    <a href="{{ route('home') }}"
       style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; padding:12px 28px; border-radius:50px; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 8px 20px -6px rgba(109,40,217,0.4);">
        <span class="material-symbols-outlined" style="font-size:18px;">storefront</span>
        Ir a la tienda
    </a>
</div>

@endsection

@push('scripts')
<script>
    function renderCart() {
        const items      = Cart.getItems();
        const container  = document.getElementById('cart-container');
        const emptyState = document.getElementById('cart-empty-state');
        const list       = document.getElementById('cart-items-list');

        if (items.length === 0) {
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
            const priceUsd  = parseFloat(item.en_oferta ? item.precio_con_descuento : item.precio);
            const subtotalUsd = priceUsd * item.cantidad;
            const itemBs    = priceUsd   * bcvRate;
            const subtotalBs= subtotalUsd* bcvRate;

            const formatBs = n => n.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            const meta = [];
            if (item.color)  meta.push('Color: ' + item.color);
            if (item.grosor) meta.push('Grosor: ' + item.grosor);
            if (item.marca)  meta.push(item.marca);

            const imgHtml = item.imagen
                ? `<img src="${item.imagen}" alt="${item.nombre}" style="width:100%;height:100%;object-fit:cover;"/>`
                : `<div style="display:flex;align-items:center;justify-content:center;height:100%;"><span class="material-symbols-outlined" style="font-size:28px;color:#c4b5fd;">image</span></div>`;

            const cartItemId = item.cartItemId || item.id;

            list.insertAdjacentHTML('beforeend', `
            <div class="cart-item-card">
                <div style="display:flex; align-items:center; gap:14px; flex:1; min-width:0;">

                    {{-- Imagen --}}
                    <a href="/producto/${item.id}" class="cart-item-img" title="${item.nombre}">
                        ${imgHtml}
                    </a>

                    {{-- Info --}}
                    <div style="flex:1; min-width:0;">
                        <a href="/producto/${item.id}" class="cart-item-name">${item.nombre}</a>
                        ${meta.length ? `<div class="cart-item-meta">${meta.join(' · ')}</div>` : ''}
                        <div class="cart-item-unit-price">Bs. ${formatBs(itemBs)} / ud</div>
                    </div>
                </div>

                {{-- Controls --}}
                <div style="display:flex; align-items:center; gap:12px; flex-shrink:0; flex-wrap:wrap;">

                    {{-- Qty stepper --}}
                    <div class="qty-stepper">
                        <button class="qty-btn" onclick="Cart.updateQty('${cartItemId}', -1)" title="Restar">
                            <span class="material-symbols-outlined" style="font-size:16px;">remove</span>
                        </button>
                        <span class="qty-value">${item.cantidad}</span>
                        <button class="qty-btn" onclick="Cart.updateQty('${cartItemId}', 1)" title="Sumar">
                            <span class="material-symbols-outlined" style="font-size:16px;">add</span>
                        </button>
                    </div>

                    {{-- Subtotal --}}
                    <div style="text-align:right; min-width:100px;">
                        <div class="cart-item-subtotal">Bs. ${formatBs(subtotalBs)}</div>
                        <div class="cart-item-ref">Ref: $${subtotalUsd.toFixed(2)}</div>
                    </div>

                    {{-- Delete --}}
                    <button class="cart-delete-btn" onclick="Cart.remove('${cartItemId}')" title="Eliminar">
                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                    </button>
                </div>
            </div>`);
        });

        const totalUsd = Cart.getTotal();
        const totalBs  = totalUsd * bcvRate;
        const formatBs = n => n.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById('cart-subtotal-items').textContent = `Subtotal (${totalQty} artículo${totalQty !== 1 ? 's' : ''})`;
        document.getElementById('cart-subtotal-price').textContent = `Bs. ${formatBs(totalBs)}`;
        document.getElementById('cart-total-price').textContent    = `Bs. ${formatBs(totalBs)}`;
        document.getElementById('cart-total-ref').textContent      = `Ref: $${totalUsd.toFixed(2)}`;
    }

    window.addEventListener('cartUpdated', renderCart);
    document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endpush
