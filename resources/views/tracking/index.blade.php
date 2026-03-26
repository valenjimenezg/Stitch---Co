@extends('layouts.app')
@section('title', 'Seguimiento de Pedido')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-3">Sigue tu Pedido</h1>
        <p class="text-slate-500 font-medium">Ingresa el número de tu orden y el correo utilizado para conocer su estado.</p>
    </div>

    {{-- Session Alerts --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl mb-8 flex items-center gap-3 font-bold text-sm">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-xl mb-8 flex items-center gap-3 font-bold text-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Form --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 mb-10 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>
        
        <form action="{{ route('tracking.track') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            @csrf
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-widest">N° de Pedido</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">#</span>
                    <input type="text" name="order_id" required placeholder="1024" value="{{ request('order_id', old('order_id')) }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-900 font-bold placeholder:text-slate-300 transition-all shadow-sm">
                </div>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-widest">Correo Electrónico</label>
                <input type="email" name="email" required placeholder="tu@email.com" value="{{ request('email', old('email')) }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-900 font-bold placeholder:text-slate-300 transition-all shadow-sm">
            </div>
            
            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-black py-3 rounded-xl transition-all shadow-lg shadow-primary/30 active:scale-[0.98] uppercase tracking-wider text-sm">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    {{-- Tracking Result --}}
    @if(isset($order))
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 pt-10 relative overflow-hidden">
            {{-- Order Header Info --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-6 mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-1">Pedido #{{ $order->id }}</h2>
                    <p class="text-sm text-slate-500 font-medium">Realizado el {{ $order->created_at->format('d M, Y - h:i A') }}</p>
                </div>
                
                @if($order->estado === 'cancelado')
                    <span class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 shadow-sm self-start sm:self-auto">
                        <span class="material-symbols-outlined text-sm">cancel</span>
                        Cancelado
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 bg-yellow-50 text-yellow-600 px-4 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-yellow-200 shadow-sm self-start sm:self-auto">
                        <span class="size-2 rounded-full bg-yellow-500 animate-pulse"></span>
                        {{ strtoupper($order->estado) }}
                    </span>
                @endif
            </div>

            @if($order->estado !== 'cancelado')
                @php
                    // Logic to define Stepper Progress
                    $step = 1;
                    if (in_array($order->estado, ['procesando', 'enviado', 'entregado'])) $step = 3; // Pago Confirmado & En Preparacion
                    if (in_array($order->estado, ['enviado', 'entregado'])) $step = 4;
                    
                    $isDelivery = $order->tipo_envio === 'envio_domicilio';
                    
                    $steps = [
                        ['id' => 1, 'icon' => 'inventory_2', 'title' => 'Pedido Recibido', 'desc' => 'Orden registrada con éxito.'],
                        ['id' => 2, 'icon' => 'payments', 'title' => 'Pago Confirmado', 'desc' => 'Hemos validado tu pago.'],
                        ['id' => 3, 'icon' => 'box_add', 'title' => 'En Preparación', 'desc' => 'Empacando tus productos.'],
                        ['id' => 4, 'icon' => $isDelivery ? 'two_wheeler' : 'store', 'title' => $isDelivery ? 'Enviado con Jheypi Rides' : 'Listo para Retirar', 'desc' => $isDelivery ? 'Tu pedido va en camino a tu destino.' : 'Te esperamos en nuestra sede.']
                    ];
                @endphp

                {{-- Horizontal Stepper --}}
                <div class="mb-12 relative px-4">
                    {{-- Progress Bar Background --}}
                    <div class="absolute left-0 top-6 w-full h-1 bg-slate-100 rounded-full"></div>
                    
                    {{-- Active Progress Bar --}}
                    <div class="absolute left-0 top-6 h-1 bg-yellow-400 rounded-full transition-all duration-1000" 
                         style="width: {{ ($step === 1) ? '15%' : (($step === 2) ? '40%' : (($step === 3) ? '50%' : '100%')) }};">
                    </div>

                    <div class="relative flex justify-between">
                        @foreach($steps as $s)
                            <div class="flex flex-col items-center z-10 w-24 text-center">
                                @if($step >= $s['id'])
                                    <div class="size-12 rounded-full bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 border-4 border-white mb-3 transition-colors">
                                        <span class="material-symbols-outlined">{{ $s['icon'] }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest leading-tight mb-1">{{ $s['title'] }}</span>
                                    @if($step === $s['id'])
                                        <p class="text-[9px] text-slate-500 font-bold leading-tight line-clamp-2">{{ $s['desc'] }}</p>
                                    @endif
                                @else
                                    <div class="size-12 rounded-full bg-white text-slate-300 flex items-center justify-center border-4 border-slate-100 mb-3 transition-colors">
                                        <span class="material-symbols-outlined">{{ $s['icon'] }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight mb-1">{{ $s['title'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Detail Widgets Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Delivery Details --}}
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <h3 class="text-sm font-black text-slate-900 tracking-widest uppercase">Detalles de Entrega</h3>
                        </div>
                        
                        @if($isDelivery)
                            <div class="mb-4">
                                <span class="text-xs text-slate-500 font-medium h-6 px-3 bg-white rounded flex items-center border border-slate-200 w-max shadow-sm mb-2 gap-1.5 focus-within:ring-primary">
                                    <span class="text-[#5D2DA8] font-black">📍 Envío Local:</span> Guanare, {{ str_replace('Parroquia ', '', explode(',', $order->direccion)[1] ?? 'Guanare') }}
                                </span>
                                <p class="text-sm font-bold text-slate-800 leading-snug">{{ $order->direccion }}</p>
                            </div>
                            <div class="bg-yellow-400/10 border border-yellow-400/20 px-4 py-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-yellow-600">motorcycle</span>
                                <p class="text-xs font-black text-yellow-700 uppercase tracking-widest">A cargo de Jheypi Rides</p>
                            </div>
                        @else
                            <div class="mb-4">
                                <p class="text-sm font-bold text-slate-800 leading-snug">Av. 23 e/ Calles 15 y 16, Sector Centro,<br>Guanare, Venezuela 3350.</p>
                                <p class="text-xs text-slate-500 font-medium mt-1">Horario: Lunes a Viernes 8:00 AM – 6:30 PM</p>
                            </div>
                            <div class="bg-emerald-50 border border-emerald-100 px-4 py-3 rounded-xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-emerald-600">storefront</span>
                                <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">Retiro Gratis en Tienda</p>
                            </div>
                        @endif
                    </div>

                    {{-- Dynamic Actions --}}
                    <div class="p-6 rounded-2xl border-2 {{ $step >= 4 || $order->estado === 'procesando' ? 'border-primary/20 bg-primary/5' : 'border-red-100 bg-red-50' }} flex flex-col justify-center items-center text-center">
                        @if($order->estado === 'pendiente')
                            <span class="material-symbols-outlined text-red-400 text-3xl mb-3">warning</span>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight mb-2">¿Necesitas hacer cambios?</h3>
                            <p class="text-xs text-slate-500 font-medium mb-5 px-4">Tu pedido aún no ha sido procesado. Puedes cancelarlo si cometiste un error.</p>
                            
                            <form action="{{ route('tracking.cancel', $order->id) }}" method="POST" onsubmit="return confirm('¿Estás totalmente seguro de que deseas cancelar este pedido?');">
                                @csrf
                                <input type="hidden" name="email" value="{{ $order->email }}">
                                <button type="submit" class="bg-white border-2 border-red-200 text-red-600 hover:bg-red-600 hover:text-white hover:border-red-600 font-black text-xs px-6 py-2.5 rounded-full uppercase tracking-widest transition-all shadow-sm">
                                    Cancelar Pedido
                                </button>
                            </form>
                        @elseif(in_array($order->estado, ['procesando', 'enviado', 'entregado']))
                            <span class="material-symbols-outlined text-primary text-3xl mb-3">receipt_long</span>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight mb-2">Facturación Digital</h3>
                            <p class="text-xs text-slate-500 font-medium mb-5 px-4">El pago de este pedido ya ha sido confirmado. Puedes descargar tu recibo oficial.</p>
                            
                            <a href="{{ route('profile.orders.invoice', $order->id) }}" target="_blank" class="bg-primary hover:bg-black text-white font-black text-xs px-6 py-3 rounded-full uppercase tracking-widest transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                                Ver Factura PDF
                            </a>
                        @endif
                    </div>
                </div>
            @else
                {{-- Cancelled State View --}}
                <div class="bg-slate-50 border border-slate-100 p-10 rounded-2xl text-center mt-4">
                    <span class="material-symbols-outlined text-slate-300 text-5xl mb-4">remove_shopping_cart</span>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Pedido Cancelado</h3>
                    <p class="text-slate-500 text-sm font-medium">Este pedido fue cancelado y no ha sido procesado. Los productos han sido liberados y no se realizará ningún envío.</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
