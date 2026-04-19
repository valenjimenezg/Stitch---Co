@extends('layouts.app')
@section('title', 'Mis Pedidos — Stitch & Co')

@push('styles')
<style>
/* ── Profile Layout (shared with index) ──────────────── */
.profile-layout { display: flex; flex-direction: column; gap: 28px; }
@media(min-width:1024px) { .profile-layout { flex-direction: row; gap: 32px; } }

.profile-sidebar { width: 100%; flex-shrink: 0; }
@media(min-width:1024px) { .profile-sidebar { width: 240px; } }

.profile-sidebar-card {
    background: #fff;
    border: 1.5px solid #f0ebff;
    border-radius: 24px;
    padding: 24px 20px;
    position: sticky; top: 88px;
    box-shadow: 0 4px 20px -8px rgba(109,40,217,0.10);
}
.profile-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; font-size: 1.35rem; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(109,40,217,0.35);
}
.profile-user-name { font-size: 14px; font-weight: 800; color: #1e1b4b; line-height: 1.2; }
.profile-user-role {
    font-size: 11px; font-weight: 600; color: #7c3aed;
    background: #f3f0ff; padding: 2px 8px; border-radius: 50px;
    display: inline-block; margin-top: 4px;
}
.profile-nav { display: flex; flex-direction: column; gap: 2px; margin-top: 20px; }
.profile-nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: 12px;
    font-size: 13.5px; font-weight: 600; color: #6b7280;
    text-decoration: none; transition: background .15s, color .15s;
}
.profile-nav-link:hover { background: #f3f0ff; color: #7c3aed; }
.profile-nav-link.active {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; box-shadow: 0 4px 14px rgba(109,40,217,0.28);
}
.profile-nav-link .material-symbols-outlined { font-size: 20px; }
.profile-nav-divider { border: none; border-top: 1px solid #f0ebff; margin: 12px 0; }
.profile-nav-logout {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 10px 14px; border-radius: 12px;
    font-size: 13.5px; font-weight: 600; color: #ef4444;
    background: transparent; border: none; cursor: pointer;
    transition: background .15s;
}
.profile-nav-logout:hover { background: #fff5f5; }
.profile-nav-logout .material-symbols-outlined { font-size: 20px; }

/* ── Orders Main ─────────────────────────────────────── */
.orders-main { flex: 1; max-width: 840px; }

.orders-page-title {
    font-size: 1.65rem; font-weight: 900; color: #1e1b4b;
    letter-spacing: -.4px; margin-bottom: 4px;
}
.orders-page-sub { font-size: 13.5px; color: #9ca3af; margin-bottom: 0; }

.orders-live-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    background: #fff; border: 1.5px solid #f0ebff;
    border-radius: 50px;
    font-size: 11px; font-weight: 700; color: #6b7280;
    box-shadow: 0 2px 8px rgba(109,40,217,0.06);
}
.orders-live-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #22c55e;
    animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

/* ── Order Card ──────────────────────────────────────── */
.order-card {
    background: #fff;
    border: 1.5px solid #f0ebff;
    border-radius: 22px;
    overflow: hidden;
    transition: box-shadow .2s, border-color .2s;
    position: relative;
}
.order-card:hover {
    box-shadow: 0 8px 32px -8px rgba(109,40,217,0.14);
    border-color: #ddd6fe;
}

/* Color strip left */
.order-strip {
    position: absolute; top: 0; left: 0;
    width: 4px; height: 100%;
    border-radius: 22px 0 0 22px;
}
.order-strip.amber   { background: linear-gradient(to bottom, #f59e0b, #d97706); }
.order-strip.blue    { background: linear-gradient(to bottom, #3b82f6, #2563eb); }
.order-strip.indigo  { background: linear-gradient(to bottom, #6366f1, #4f46e5); }
.order-strip.emerald { background: linear-gradient(to bottom, #10b981, #059669); }
.order-strip.slate   { background: linear-gradient(to bottom, #94a3b8, #64748b); }

/* Card header */
.order-card-header {
    padding: 14px 20px 13px 26px;
    display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
    gap: 8px;
    border-bottom: 1px solid #f8f7ff;
    background: #fcfbff;
}

/* Status badge */
.order-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .08em;
}
.order-status-badge.amber   { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.order-status-badge.blue    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
.order-status-badge.indigo  { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
.order-status-badge.emerald { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.order-status-badge.slate   { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

.order-id { font-size: 12px; font-weight: 800; color: #a1a1aa; font-family: monospace; letter-spacing: -.5px; }
.order-invoice-tag {
    font-size: 11px; font-weight: 700; color: #059669;
    background: #ecfdf5; padding: 4px 10px; border-radius: 8px;
    border: 1px solid #a7f3d0;
}
.order-time { font-size: 11px; font-weight: 600; color: #9ca3af; white-space: nowrap; }

/* ── Card body: GRID layout ─────────────────────────── */
.order-card-body {
    padding: 18px 20px 20px 26px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 18px;
}
@media(min-width: 900px) {
    .order-card-body {
        grid-template-columns: 1fr 240px;
        gap: 20px;
        align-items: start;
    }
}

/* Product thumbnail */
.order-product-img {
    width: 52px; height: 52px; border-radius: 13px;
    background: #f8f7ff; border: 1.5px solid #ede9fe;
    overflow: hidden; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    transition: border-color .15s;
}
.order-product-img:hover { border-color: #c4b5fd; }
.order-product-img img { width: 100%; height: 100%; object-fit: cover; }

.order-product-mini {
    width: 28px; height: 28px; border-radius: 7px;
    background: #f8f7ff; border: 1px solid #ede9fe;
    overflow: hidden; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.order-product-mini img { width: 100%; height: 100%; object-fit: cover; }

.order-product-link {
    font-size: 12px; font-weight: 700; color: #1e1b4b;
    text-decoration: none; transition: color .15s;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    display: block;
}
.order-product-link:hover { color: #7c3aed; text-decoration: underline; }

/* Delivery badge */
.order-delivery-badge {
    display: flex; align-items: center; gap: 9px;
    background: #f8f7ff; border-left: 3px solid #7c3aed;
    border-radius: 0 11px 11px 0;
    padding: 8px 13px;
    width: 100%;
}

/* ── RIGHT COLUMN ───────────────────────────────────── */
.order-right-col {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
@media(min-width: 900px) {
    .order-right-col {
        border-left: 1px solid #f0ebff;
        padding-left: 20px;
    }
}

/* Pricing */
.order-price-label {
    font-size: 9px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: #9ca3af; margin-bottom: 3px;
}
.order-price-big {
    font-size: 1.6rem; font-weight: 900; letter-spacing: -.5px; line-height: 1.05;
    margin-bottom: 5px;
}
.order-price-ref {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 700; color: #9ca3af;
    background: #f8f7ff; padding: 3px 8px; border-radius: 6px;
    border: 1px solid #ede9fe;
}
.order-payment-method { font-size: 9px; font-weight: 800; color: #a1a1aa; text-transform: uppercase; letter-spacing: .08em; margin-top: 8px; margin-bottom: 3px; }
.order-payment-ref {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 700; font-family: monospace; color: #7c3aed;
    background: #f3f0ff; padding: 4px 8px; border-radius: 8px;
    border: 1px solid #ddd6fe;
}

/* Action buttons */
.order-btn-primary {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 14px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em;
    border-radius: 13px; border: none; cursor: pointer;
    text-decoration: none;
    box-shadow: 0 5px 16px -4px rgba(109,40,217,0.4);
    transition: transform .15s, box-shadow .15s;
}
.order-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 22px -4px rgba(109,40,217,0.5); color: #fff; }

.order-btn-outline {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 14px;
    background: #fff; color: #4c1d95;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em;
    border-radius: 13px; border: 1.5px solid #ddd6fe;
    cursor: pointer; text-decoration: none;
    transition: background .15s, border-color .15s;
}
.order-btn-outline:hover { background: #f5f3ff; border-color: #c4b5fd; }

.order-btn-warning {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 14px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff; font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em;
    border-radius: 13px; border: none; cursor: pointer;
    box-shadow: 0 4px 14px -4px rgba(217,119,6,0.4);
    transition: transform .15s;
}
.order-btn-warning:hover { transform: translateY(-1px); }

.order-btn-danger {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 14px;
    background: #fff; color: #ef4444;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em;
    border-radius: 13px; border: 1.5px solid #fee2e2;
    cursor: pointer; transition: background .15s;
}
.order-btn-danger:hover { background: #fff5f5; }

.order-btn-disabled {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 10px 14px;
    background: #f8f7ff; color: #a1a1aa;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .07em;
    border-radius: 13px; border: 1.5px solid #ede9fe;
    cursor: not-allowed;
}

/* Empty state */
.orders-empty {
    background: #fff; border: 1.5px solid #f0ebff;
    border-radius: 24px; padding: 60px 24px; text-align: center;
}
.orders-empty-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, #f3f0ff, #ede9fe);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
}
</style>
@endpush

@section('content')

<div class="profile-layout">

    {{-- ── Sidebar ──────────────────────────────────────── --}}
    <aside class="profile-sidebar">
        <div class="profile-sidebar-card">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:4px;">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div>
                    <div class="profile-user-name">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</div>
                    <span class="profile-user-role">Cliente</span>
                </div>
            </div>
            <nav class="profile-nav">
                <a href="{{ route('profile.index') }}"
                   class="profile-nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">person</span> Información Personal
                </a>
                <a href="{{ route('profile.orders') }}"
                   class="profile-nav-link {{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">shopping_bag</span> Mis Pedidos
                </a>
                <a href="{{ route('wishlist.index') }}"
                   class="profile-nav-link {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">favorite</span> Lista de Deseos
                </a>
                <hr class="profile-nav-divider">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="profile-nav-logout">
                        <span class="material-symbols-outlined">logout</span> Cerrar Sesión
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────── --}}
    <section class="orders-main">

        <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:28px;">
            <div>
                <h1 class="orders-page-title">Mis Pedidos</h1>
                <p class="orders-page-sub">Gestiona y rastrea el estado de tus compras recientes.</p>
            </div>
            <div class="orders-live-badge">
                <span class="orders-live-dot"></span>
                Actualizado hace un momento
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:24px;">
            @forelse($ventas as $venta)
                @php
                    $requiresReference = in_array($venta->metodo_pago, ['pago_movil', 'transferencia', 'zelle', 'zelle_usd', 'debito_inmediato', 'transferencia_p2p']);
                    $isUnpaid = in_array($venta->estado, ['pendiente', 'pending']) && $requiresReference && empty($venta->referencia_pago);
                    $isPendiente = in_array($venta->estado, ['pendiente', 'pending']);
                    $isPaid = in_array($venta->estado, ['procesando', 'enviado', 'entregado']);

                    if ($isUnpaid) {
                        $statusText = 'Esperando Pago'; $icon = 'schedule'; $badgeColor = 'amber';
                    } elseif ($venta->estado === 'pendiente') {
                        $statusText = 'Verificando Pago'; $icon = 'hourglass_top'; $badgeColor = 'amber';
                    } elseif ($venta->estado === 'procesando') {
                        $statusText = 'Preparando Paquete'; $icon = 'inventory_2'; $badgeColor = 'blue';
                    } elseif ($venta->estado === 'enviado') {
                        $statusText = 'En Tránsito'; $icon = 'local_shipping'; $badgeColor = 'indigo';
                    } elseif ($venta->estado === 'entregado') {
                        $statusText = 'Entregado'; $icon = 'check_circle'; $badgeColor = 'emerald';
                    } else {
                        $statusText = ucfirst($venta->estado); $icon = 'info'; $badgeColor = 'slate';
                    }
                    $firstItem = $venta->detalles->first();
                @endphp

                <div class="order-card">
                    {{-- Color strip --}}
                    <div class="order-strip {{ $badgeColor }}"></div>

                    {{-- Header --}}
                    <div class="order-card-header">
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            <span class="order-status-badge {{ $badgeColor }}">
                                <span class="material-symbols-outlined" style="font-size:13px;">{{ $icon }}</span>
                                {{ $statusText }}
                            </span>
                            <span class="order-id">#ORD-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</span>
                            @if($isPaid && $venta->invoice_number)
                                <span class="order-invoice-tag">Factura {{ $venta->invoice_number }}</span>
                            @endif
                        </div>
                        <span class="order-time">{{ $venta->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Body --}}
                    <div class="order-card-body">

                        {{-- LEFT: Product info --}}
                        <div style="display:flex; flex-direction:column; gap:12px; min-width:0;">
                            <div style="display:flex; gap:12px; align-items:flex-start;">
                                {{-- Thumbnail --}}
                                @if($firstItem && $firstItem->variante && $firstItem->variante->producto)
                                    <a href="{{ route('products.show', $firstItem->variante->id) }}" class="order-product-img" title="Ver producto">
                                        @if($firstItem->variante->imagen)
                                            <img src="{{ asset($firstItem->variante->imagen) }}" alt="{{ $firstItem->variante->producto->nombre }}">
                                        @else
                                            <span class="material-symbols-outlined" style="font-size:24px; color:#c4b5fd;">shopping_basket</span>
                                        @endif
                                    </a>
                                @else
                                    <div class="order-product-img">
                                        <span class="material-symbols-outlined" style="font-size:24px; color:#c4b5fd;">shopping_basket</span>
                                    </div>
                                @endif

                                {{-- Items list --}}
                                <div style="flex:1; min-width:0;">
                                    <h3 style="font-size:14px; font-weight:800; color:#1e1b4b; margin-bottom:8px;">Artículos adquiridos</h3>
                                    <div style="display:flex; flex-direction:column; gap:6px; max-height:100px; overflow-y:auto; padding-right:4px;">
                                        @foreach($venta->detalles as $detalle)
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                @if($detalle->variante && $detalle->variante->producto)
                                                    @if($detalle->variante->imagen)
                                                        <div class="order-product-mini">
                                                            <img src="{{ asset($detalle->variante->imagen) }}" alt="">
                                                        </div>
                                                    @else
                                                        <div class="order-product-mini" style="display:flex; align-items:center; justify-content:center;">
                                                            <span class="material-symbols-outlined" style="font-size:14px; color:#c4b5fd;">inventory_2</span>
                                                        </div>
                                                    @endif
                                                    <div style="min-width:0; flex:1;">
                                                        <a href="{{ route('products.show', $detalle->variante->id) }}" style="font-size:12px; font-weight:700; color:#1e1b4b; text-decoration:none; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $detalle->variante->producto->nombre }}" onmouseover="this.style.color='#7c3aed'" onmouseout="this.style.color='#1e1b4b'">{{ $detalle->variante->producto->nombre }}</a>
                                                        <p style="font-size:10px; color:#9ca3af; font-weight:500;">Cant: <strong style="color:#4b5563;">{{ $detalle->cantidad }}</strong>{{ $detalle->variante->color ? ' &middot; '.$detalle->variante->color : '' }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>{{-- /thumbnail+list row --}}

                            {{-- Delivery badge --}}
                            @if($venta->tipo_envio === 'retiro_tienda')
                                <div class="order-delivery-badge" style="border-left-color:#94a3b8;">
                                    <span class="material-symbols-outlined" style="font-size:20px; color:#64748b;">store</span>
                                    <div>
                                        <p style="font-size:9px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:1px;">Punto de Entrega</p>
                                        <p style="font-size:12px; font-weight:700; color:#1e1b4b;">Retiro en Tienda Física</p>
                                    </div>
                                </div>
                            @else
                                <div class="order-delivery-badge"
                                     style="border-left-color:{{ in_array($venta->estado, ['entregado','enviado']) ? '#6366f1' : '#7c3aed' }};">
                                    <span class="material-symbols-outlined" style="font-size:20px; color:{{ in_array($venta->estado, ['entregado','enviado']) ? '#6366f1' : '#7c3aed' }};">
                                        {{ $venta->estado === 'entregado' ? 'home_pin' : ($venta->estado === 'enviado' ? 'local_shipping' : 'two_wheeler') }}
                                    </span>
                                    <div>
                                        <p style="font-size:9px; font-weight:800; color:{{ in_array($venta->estado, ['entregado','enviado']) ? '#4f46e5' : '#7c3aed' }}; text-transform:uppercase; letter-spacing:.08em; margin-bottom:1px;">
                                            {{ $venta->estado === 'entregado' ? 'Paquete Entregado' : ($venta->estado === 'enviado' ? 'En Tránsito' : 'Logística Express') }}
                                        </p>
                                        <p style="font-size:12px; font-weight:700; color:#1e1b4b;">{{ $venta->calle_envio ?? 'Envío a Domicilio' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- RIGHT: Pricing + Actions --}}
                        <div class="order-right-col">

                            {{-- Price block --}}
                            <div>
                                <p class="order-price-label">{{ $isPendiente ? 'Saldo Pendiente' : 'Total Cancelado' }}</p>
                                <p class="order-price-big" style="color:{{ $isPendiente ? '#1e1b4b' : '#7c3aed' }};">{{ bs($venta->total_amount ?? 0, false, $venta->tasa_bcv_aplicada) }}</p>
                                <span class="order-price-ref">
                                    <span class="material-symbols-outlined" style="font-size:12px;">payments</span>
                                    Ref: ${{ number_format((float)($venta->total_amount ?? 0), 2) }}
                                </span>
                                @if($venta->referencia_pago)
                                    <div style="margin-top:8px;">
                                        <p class="order-payment-method">{{ str_replace('_', ' ', $venta->metodo_pago) }}</p>
                                        <span class="order-payment-ref">
                                            <span class="material-symbols-outlined" style="font-size:13px;">receipt_long</span>
                                            {{ $venta->referencia_pago }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Action buttons --}}
                            <div style="display:flex; flex-direction:column; gap:8px; width:100%; max-width:280px;">
                                @if($isUnpaid)
                                    <form action="{{ route('profile.orders.resume', $venta->id) }}" method="POST" style="width:100%;">
                                        @csrf
                                        <button type="submit" class="order-btn-warning">
                                            <span class="material-symbols-outlined" style="font-size:16px;">credit_card</span>
                                            Continuar Compra
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($venta->estado, ['pendiente', 'pending']) && empty($venta->referencia_pago))
                                    <form action="{{ route('profile.orders.cancel', $venta->id) }}" method="POST" style="width:100%;" onsubmit="return confirm('¿Deseas cancelar orden? Se liberará el stock asignado permanentemente.')">
                                        @csrf
                                        <button type="submit" class="order-btn-danger">
                                            <span class="material-symbols-outlined" style="font-size:16px;">delete_forever</span>
                                            Cancelar Pedido
                                        </button>
                                    </form>
                                @endif

                                @if(!in_array($venta->estado, ['cancelada']))
                                    <a href="{{ route('profile.orders.invoice', $venta->id) }}" target="_blank" class="order-btn-primary">
                                        <span class="material-symbols-outlined" style="font-size:16px;">print</span>
                                        @if($venta->monto_abonado > 0 && $venta->monto_abonado < $venta->total_amount)
                                            Descargar Ticket de Abono
                                        @else
                                            Descargar Factura
                                        @endif
                                    </a>

                                    @if($venta->tipo_envio !== 'retiro_tienda' && !in_array($venta->estado, ['pendiente', 'pending']))
                                        <button type="button"
                                                onclick="showTrackingTimeline('{{ $venta->id }}', '{{ $venta->estado }}', '{{ htmlspecialchars($venta->calle_envio ?? 'Domicilio Valido', ENT_QUOTES) }}')"
                                                class="order-btn-outline">
                                            <span class="material-symbols-outlined" style="font-size:16px;">local_shipping</span>
                                            Rastrear Envío
                                        </button>
                                    @endif
                                @else
                                    <div class="order-btn-disabled">
                                        <span class="material-symbols-outlined" style="font-size:16px;">block</span>
                                        Orden Cancelada
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <div class="orders-empty">
                    <div class="orders-empty-icon">
                        <span class="material-symbols-outlined" style="font-size:36px; color:#a78bfa; font-variation-settings:'FILL' 1;">shopping_bag</span>
                    </div>
                    <h3 style="font-size:1.15rem; font-weight:800; color:#1e1b4b; margin-bottom:6px;">Aún no tienes pedidos</h3>
                    <p style="font-size:13px; color:#9ca3af; margin-bottom:24px;">Explora nuestro catálogo y encuentra los mejores productos.</p>
                    <a href="{{ route('home') }}" class="order-btn-primary" style="max-width:240px; margin:0 auto;">
                        <span class="material-symbols-outlined" style="font-size:16px;">storefront</span>
                        Ir a la tienda
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Script interactivo --}}
    @push('scripts')
    <script>
        function cancelarPedidoDemo() {
            Swal.fire({
                title: '¿Deseas cancelar orden?',
                text: "Se notificará a soporte y se liberará el stock asignado permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#f8fafc',
                confirmButtonText: 'Sí, cancelar orden',
                cancelButtonText: '<span style="color: #475569">No, mantener</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                    confirmButton: 'font-black uppercase tracking-widest text-[11px] px-8 py-3.5 rounded-xl shadow-lg shadow-red-500/30',
                    cancelButton: 'font-black uppercase tracking-widest text-[11px] px-8 py-3.5 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const card = document.getElementById('demo-card-pending');
                    card.style.transformOrigin = 'top center';
                    card.style.transform = 'scale(0.95)';
                    card.style.opacity = '0';
                    card.style.marginTop = `-${card.offsetHeight}px`;
                    card.style.marginBottom = '0px';
                    setTimeout(() => {
                        card.style.display = 'none';
                        Swal.fire({
                            toast: true, position: 'bottom-right', icon: 'success',
                            title: 'Orden #ORD-001025 cancelada con éxito.',
                            showConfirmButton: false, timer: 4000, timerProgressBar: true,
                            customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' }
                        });
                    }, 500);
                }
            });
        }

        function showTrackingTimeline(id, estado, direccion) {
            let steps = [
                { status: 'pendiente', label: 'Confirmación de Orden', desc: 'Pago verificado exitosamente.', icon: 'receipt_long' },
                { status: 'procesando', label: 'Preparado en Sede', desc: 'Paquete listo para recolección.', icon: 'inventory_2' },
                { status: 'enviado', label: 'Mototaxi en Camino', desc: 'El repartidor va hacia tu dirección.', icon: 'two_wheeler' },
                { status: 'entregado', label: 'Entregado al Cliente', desc: 'Pedido finalizado con éxito.', icon: 'where_to_vote' }
            ];

            let currentStateIndex = steps.findIndex(s => s.status === estado);
            if(currentStateIndex === -1) currentStateIndex = 0;

            let html = `
                <div class="text-left mt-1">
                    <div style="background:linear-gradient(135deg,#f8f7ff,#fff); padding:14px; border-radius:16px; border:1.5px solid #ede9fe; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#f3f0ff,#ede9fe); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:18px; color:#7c3aed;">pin_drop</span>
                        </div>
                        <div>
                            <p style="font-size:9px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:.1em; margin-bottom:2px;">Destino de Entrega</p>
                            <p style="font-size:13px; font-weight:700; color:#1e1b4b; line-height:1.3;">${direccion}</p>
                        </div>
                    </div>

                    <div style="padding:0 6px;">
                        <p style="font-size:9px; font-weight:800; text-transform:uppercase; color:#9ca3af; letter-spacing:.1em; margin-bottom:18px;">Secuencia Logística</p>
                        <div class="relative ml-4 space-y-6 pb-2">
                            <div class="absolute left-[-1px] top-2 bottom-4 w-0.5 bg-slate-100 z-0"></div>
                            <div class="absolute left-[-1px] top-2 w-0.5 bg-primary z-0 transition-all duration-500" style="height: ${currentStateIndex === 0 ? '0' : (currentStateIndex / (steps.length - 1)) * 100}%"></div>
            `;

            steps.forEach((step, index) => {
                let isPast = index < currentStateIndex;
                let isCurrent = index === currentStateIndex;
                let isFuture = index > currentStateIndex;

                let dotClass = isPast || isCurrent
                    ? 'bg-primary border-primary ring-4 ring-primary/20 shadow-md scale-110'
                    : 'bg-white border-slate-200 shadow-sm';

                let isCheckMark = isPast;

                let textColor = isCurrent ? 'text-primary' : (isPast ? 'text-slate-800' : 'text-slate-400');
                let descColor = isCurrent ? 'text-slate-600' : (isPast ? 'text-slate-500' : 'text-slate-300');
                let iconColor = isCurrent ? 'text-primary' : (isPast ? 'text-slate-600' : 'text-slate-300');

                html += `
                            <div class="relative pl-8 z-10 group">
                                <div class="absolute -left-[7px] top-1.5 size-[14px] rounded-full border-2 ${dotClass} z-10 flex items-center justify-center transition-all"></div>
                                <div class="flex items-start gap-3 bg-white ${isCurrent ? 'p-3 -mt-2 -ml-2 rounded-xl shadow-[0_0_15px_rgba(0,0,0,0.05)] border border-primary/10' : ''}">
                                    <div class="relative shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined text-[20px] ${iconColor}" ${isCurrent ? "style='font-variation-settings: \"FILL\" 1;'" : ""}>${step.icon}</span>
                                        ${isCheckMark ? `<span class="absolute -bottom-1 -right-1 size-3.5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center"><span class="material-symbols-outlined text-white text-[9px] font-black">check</span></span>` : ''}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="${textColor} font-black text-[13px] uppercase tracking-wide flex items-center justify-between">
                                            ${step.label}
                                            ${isCurrent ? '<span class="px-2 py-0.5 bg-primary/10 text-primary text-[9px] rounded-full ml-2 shrink-0 animate-pulse">ACTUAL</span>' : ''}
                                        </h4>
                                        <p class="${descColor} text-[11px] font-medium leading-snug mt-0.5">
                                            ${isFuture ? 'Pendiente' : step.desc}
                                        </p>
                                    </div>
                                </div>
                            </div>
                `;
            });

            html += `
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title: `<span class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[24px]">explore</span> Orden ORD-${String(id).padStart(6, '0')}</span>`,
                html: html,
                showConfirmButton: true,
                confirmButtonText: 'Cerrar Rastreador',
                confirmButtonColor: '#8b5cf6',
                customClass: {
                    popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                    confirmButton: 'w-full bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-black text-[11px] px-8 py-4 rounded-xl uppercase tracking-widest transition-colors border border-slate-200 mt-4',
                    htmlContainer: 'px-2 pb-2'
                }
            });
        }
    </script>
    @endpush

</div>

@endsection
