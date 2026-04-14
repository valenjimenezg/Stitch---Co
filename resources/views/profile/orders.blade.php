@extends('layouts.app')

@section('title', 'Mis Pedidos — Stitch & Co')

@section('content')

<div class="flex flex-col lg:flex-row gap-10 pb-20">

    {{-- Sidebar --}}
    <aside class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-24">
            <div class="flex items-center gap-4 mb-6">
                <div class="size-14 rounded-full bg-primary/10 border-2 border-primary flex items-center justify-center text-primary font-black text-xl">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</h3>
                    <p class="text-xs font-medium text-primary">Cliente</p>
                </div>
            </div>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.index') ? 'bg-primary text-white' : 'text-slate-600 hover:bg-primary/5 hover:text-primary' }}">
                    <span class="material-symbols-outlined">person</span> Información Personal
                </a>
                <a href="{{ route('profile.orders') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.orders') ? 'bg-primary text-white' : 'text-slate-600 hover:bg-primary/5 hover:text-primary' }}">
                    <span class="material-symbols-outlined">shopping_bag</span> Mis Pedidos
                </a>
                <a href="{{ route('wishlist.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:bg-primary/5 hover:text-primary font-medium transition-colors">
                    <span class="material-symbols-outlined">favorite</span> Lista de Deseos
                </a>
                <div class="border-t border-slate-100 mt-4 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex w-full items-center gap-3 px-4 py-3 rounded-lg text-rose-500 hover:bg-rose-50 font-medium transition-colors">
                            <span class="material-symbols-outlined">logout</span> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    {{-- Main Content (MOCK DE DEMO CLASE) --}}
    <section class="flex-1 max-w-4xl">
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Mis Pedidos</h1>
                <p class="text-slate-500 font-medium text-sm">Gestiona y rastrea el estado de tus compras recientes.</p>
            </div>
            <div class="px-5 py-2.5 bg-white rounded-full border border-slate-200 shadow-sm text-xs font-bold text-slate-600 flex items-center gap-2 w-max">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Actualizado hace un momento
            </div>
        </div>

        <div class="space-y-8">
            @forelse($ventas as $venta)
                @php
                    $requiresReference = in_array($venta->metodo_pago, ['pago_movil', 'transferencia', 'zelle', 'zelle_usd', 'debito_inmediato', 'transferencia_p2p']);
                    $isUnpaid = in_array($venta->estado, ['pendiente', 'pending']) && $requiresReference && empty($venta->referencia_pago);
                    $isPendiente = in_array($venta->estado, ['pendiente', 'pending']);
                    $isPaid = in_array($venta->estado, ['procesando', 'enviado', 'entregado']);
                    
                    if ($isUnpaid) {
                        $statusText = 'Esperando Pago';
                        $colorBg = 'bg-amber-100 text-amber-700 border-amber-200';
                        $icon = 'schedule';
                        $gradient = 'from-amber-400 to-amber-500';
                        $badgeColor = 'amber';
                    } elseif ($venta->estado === 'pendiente') {
                        $statusText = 'Verificando Pago';
                        $colorBg = 'bg-amber-100 text-amber-700 border-amber-200';
                        $icon = 'hourglass_top';
                        $gradient = 'from-amber-400 to-amber-500';
                        $badgeColor = 'amber';
                    } elseif ($venta->estado === 'procesando') {
                        $statusText = 'Preparando Paquete';
                        $colorBg = 'bg-blue-100 text-blue-700 border-blue-200';
                        $icon = 'inventory_2';
                        $gradient = 'from-blue-400 to-blue-500';
                        $badgeColor = 'blue';
                    } elseif ($venta->estado === 'enviado') {
                        $statusText = 'En Tránsito';
                        $colorBg = 'bg-indigo-100 text-indigo-700 border-indigo-200';
                        $icon = 'local_shipping';
                        $gradient = 'from-indigo-400 to-indigo-500';
                        $badgeColor = 'indigo';
                    } elseif ($venta->estado === 'entregado') {
                        $statusText = 'Entregado';
                        $colorBg = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                        $icon = 'check_circle';
                        $gradient = 'from-emerald-400 to-emerald-500';
                        $badgeColor = 'emerald';
                    } else {
                        $statusText = ucfirst($venta->estado);
                        $colorBg = 'bg-slate-100 text-slate-700 border-slate-200';
                        $icon = 'info';
                        $gradient = 'from-slate-400 to-slate-500';
                        $badgeColor = 'slate';
                    }
                    $firstItem = $venta->detalles->first();
                @endphp
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative transition-all duration-700 transform opacity-100 scale-100 origin-top group">
                    <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b {{ $gradient }}"></div>
                    
                    {{-- Card Header --}}
                    <div class="px-8 py-5 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <span class="{{ $colorBg }} border text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">{{ $icon }}</span>
                                {{ $statusText }}
                            </span>
                            <span class="text-sm font-black text-slate-400 font-mono tracking-tighter">#ORD-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</span>
                            @if($isPaid && $venta->invoice_number)
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">Factura {{ $venta->invoice_number }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ $venta->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="p-8 flex flex-col xl:flex-row gap-8 items-start xl:items-center justify-between">
                        
                        {{-- Product Info --}}
                        <div class="flex-1 flex flex-col sm:flex-row gap-5 w-full">
                            @if($firstItem && $firstItem->variante && $firstItem->variante->producto)
                                <a href="{{ route('products.show', $firstItem->variante->id) }}" class="size-16 rounded-xl bg-slate-50 border border-slate-200 p-1 flex items-center justify-center shrink-0 overflow-hidden shadow-sm hover:border-primary/50 transition-colors group/img" title="Ver producto de nuevo">
                                    @if($firstItem->variante->imagen)
                                        <img src="{{ asset($firstItem->variante->imagen) }}" alt="{{ $firstItem->variante->producto->nombre }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="material-symbols-outlined text-3xl text-slate-400 group-hover/img:text-primary transition-colors">
                                            {{ $venta->estado === 'procesando' ? 'inventory_2' : ($venta->estado === 'enviado' || $venta->estado === 'entregado' ? 'mark_email_read' : 'shopping_basket') }}
                                        </span>
                                    @endif
                                </a>
                            @else
                                <div class="size-16 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden shadow-sm group-hover:border-{{ $badgeColor }}-400/30 transition-colors">
                                    <span class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-{{ $badgeColor }}-500 transition-colors">
                                        {{ $venta->estado === 'procesando' ? 'inventory_2' : ($venta->estado === 'enviado' || $venta->estado === 'entregado' ? 'mark_email_read' : 'shopping_basket') }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-xl font-black text-slate-900 leading-tight mb-1">
                                    @if($firstItem && $firstItem->variante && $firstItem->variante->producto)
                                        <a href="{{ route('products.show', $firstItem->variante->id) }}" class="hover:text-primary hover:underline transition-colors cursor-pointer" title="Ver producto de nuevo">
                                            {{ $firstItem->variante->producto->nombre }}
                                        </a>
                                    @else
                                        Pedido
                                    @endif
                                    @if($venta->detalles->count() > 1) <span class="text-sm font-bold text-slate-400">y más...</span> @endif
                                </h3>
                                <p class="text-sm text-slate-500 font-medium mb-4 line-clamp-1">
                                    {{ $venta->detalles->sum('cantidad') }} Artículo(s) {{ $isPendiente ? 'esperando confirmación.' : 'en esta compra.' }}
                                </p>
                                
                                {{-- Delivery Badge --}}
                                @if($venta->tipo_envio === 'retiro_tienda')
                                <div class="flex items-center gap-3 bg-gradient-to-r from-slate-100 to-transparent border-l-2 border-slate-300 py-2 w-full max-w-sm rounded-r-xl">
                                    <span class="material-symbols-outlined text-slate-500 text-2xl ml-3">store</span>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Punto de Entrega</p>
                                        <p class="text-xs font-bold text-slate-800">Retiro en Tienda Física</p>
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center gap-3 bg-gradient-to-r {{ $venta->estado === 'entregado' || $venta->estado === 'enviado' ? 'from-indigo-100 border-indigo-300' : 'from-yellow-400/10 border-yellow-400' }} border-l-2 py-2 w-full max-w-sm rounded-r-xl">
                                    <span class="material-symbols-outlined {{ $venta->estado === 'entregado' || $venta->estado === 'enviado' ? 'text-indigo-600' : 'text-yellow-600' }} text-2xl ml-3">
                                        {{ $venta->estado === 'entregado' ? 'home_pin' : ($venta->estado === 'enviado' ? 'local_shipping' : 'two_wheeler') }}
                                    </span>
                                    <div>
                                        <p class="text-[10px] font-black {{ $venta->estado === 'entregado' || $venta->estado === 'enviado' ? 'text-indigo-700' : 'text-yellow-700' }} uppercase tracking-widest leading-none mb-1 text-shadow-sm">
                                            {{ $venta->estado === 'entregado' ? 'Paquete Entregado' : ($venta->estado === 'enviado' ? 'En Tránsito' : 'Logística Express') }}
                                        </p>
                                        <p class="text-xs font-bold text-slate-800">
                                            {{ $venta->calle_envio ?? 'Envío a Domicilio' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Actions & Pricing --}}
                        <div class="shrink-0 flex flex-col items-start xl:items-end w-full xl:w-auto border-t xl:border-t-0 border-slate-100 pt-6 xl:pt-0">
                            <div class="mb-5 xl:text-right flex flex-col items-start xl:items-end gap-1">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $isPendiente ? 'Saldo Pendiente' : 'Total Cancelado' }}</p>
                                <p class="text-3xl font-black {{ $isPendiente ? 'text-slate-900' : 'text-primary' }} tracking-tighter">{{ bs($venta->total_amount ?? 0, false, $venta->tasa_bcv_aplicada) }}</p>
                                <p class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-100 mt-1 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">payments</span> Ref: ${{ number_format((float)($venta->total_amount ?? 0), 2) }}
                                </p>
                                @if($venta->referencia_pago)
                                    <div class="mt-2 text-right">
                                        <p class="text-[9px] font-black tracking-widest uppercase text-slate-400 mb-0.5">{{ str_replace('_', ' ', $venta->metodo_pago) }}</p>
                                        <p class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-primary bg-primary/10 border border-primary/20 px-2 py-1 rounded-md">
                                            <span class="material-symbols-outlined text-[14px]">receipt_long</span> 
                                            {{ $venta->referencia_pago }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto mt-4 xl:mt-0">
                                @if($isUnpaid)
                                    <form action="{{ route('profile.orders.resume', $venta->id) }}" method="POST" class="w-full xl:w-auto">
                                        @csrf
                                        <button type="submit" class="w-full xl:w-auto bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-black text-xs px-8 py-4 rounded-xl uppercase tracking-widest transition-all shadow-md shadow-yellow-400/20 flex items-center justify-center gap-2 group/btn">
                                            <span class="material-symbols-outlined text-xl group-hover/btn:-translate-y-0.5 transition-transform">credit_card</span>
                                            Continuar Compra
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($venta->estado, ['pendiente', 'pending']) && empty($venta->referencia_pago))
                                    <form action="{{ route('profile.orders.cancel', $venta->id) }}" method="POST" class="w-full xl:w-auto" onsubmit="return confirm('¿Deseas cancelar orden? Se liberará el stock asignado permanentemente.')">
                                        @csrf
                                        <button type="submit" class="w-full xl:w-auto bg-white border border-red-200 text-red-500 hover:bg-red-50 font-black text-xs px-8 py-4 rounded-xl uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-2 group/btn">
                                            <span class="material-symbols-outlined text-lg group-hover/btn:rotate-12 transition-transform">delete_forever</span>
                                            Cancelar Pedido
                                        </button>
                                    </form>
                                @endif

                                @if(!in_array($venta->estado, ['cancelada']))
                                        <div class="w-full xl:w-80">
                                            <a href="{{ route('profile.orders.invoice', $venta->id) }}" target="_blank"
                                                    class="w-full bg-primary hover:bg-primary/90 text-white font-black text-[11px] sm:text-xs px-6 py-4 rounded-xl uppercase tracking-widest transition-all shadow-xl shadow-primary/20 active:scale-95 flex items-center justify-center gap-2 group/btn border border-primary">
                                                <span class="material-symbols-outlined text-emerald-400 text-[18px] group-hover/btn:-translate-y-1 transition-transform">print</span>
                                                @if($venta->monto_abonado > 0 && $venta->monto_abonado < $venta->total_amount)
                                                    Descargar Ticket de Abono
                                                @else
                                                    Descargar Factura
                                                @endif
                                            </a>
                                        </div>
                                @else
                                    <div class="w-full xl:w-auto bg-slate-50 text-slate-400 font-black text-[10px] px-6 py-4 rounded-xl uppercase tracking-widest flex items-center justify-center gap-2 border border-slate-200 cursor-not-allowed">
                                        <span class="material-symbols-outlined text-slate-400 text-lg">hourglass_empty</span>
                                        Orden Cancelada
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="size-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-4xl text-slate-300">shopping_bag</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Aún no tienes pedidos</h3>
                    <p class="text-slate-500 font-medium mb-6">Explora nuestro catálogo y encuentra los mejores productos.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-primary text-white font-black text-xs px-8 py-4 rounded-xl uppercase tracking-widest hover:bg-primary/90 transition-all shadow-xl shadow-primary/20">
                        Ir a la tienda
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Script interactivo para la cancelación local premium --}}
    @push('scripts')
    <script>
        function cancelarPedidoDemo() {
            Swal.fire({
                title: '¿Deseas cancelar orden?',
                text: "Se notificará a soporte y se liberará el stock asignado permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // red-500
                cancelButtonColor: '#f8fafc',  // slate-50
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
                    
                    // Efecto de desvanecimiento avanzado de layout colapsable
                    card.style.transformOrigin = 'top center';
                    card.style.transform = 'scale(0.95)';
                    card.style.opacity = '0';
                    card.style.marginTop = `-${card.offsetHeight}px`;
                    card.style.marginBottom = '0px';
                    
                    setTimeout(() => {
                        card.style.display = 'none';
                        
                        Swal.fire({
                            toast: true,
                            position: 'bottom-right',
                            icon: 'success',
                            title: 'Orden #ORD-001025 cancelada con éxito.',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'rounded-2xl shadow-xl border border-slate-100'
                            }
                        });
                    }, 500); 
                }
            });
        }
    </script>
    @endpush

</div>

@endsection
