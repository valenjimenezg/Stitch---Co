@extends('layouts.admin')

@section('title', 'Detalle del Pedido #' . str_pad($venta->id, 5, '0', STR_PAD_LEFT))

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-slate-400 hover:text-primary transition-colors flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
        </a>
        <h2 class="text-2xl font-bold text-slate-900">
            Pedido <span class="text-primary font-mono">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</span>
        </h2>
    </div>
    
    @php
        $colores = [
            'pendiente'   => 'bg-amber-100 text-amber-700 border-amber-200',
            'procesando'  => 'bg-blue-100 text-blue-700 border-blue-200',
            'enviado'     => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'entregado'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'cancelada'   => 'bg-red-100 text-red-700 border-red-200',
        ];
        $color = $colores[$venta->estado] ?? 'bg-slate-100 text-slate-700';
    @endphp
    <span class="px-3 py-1.5 rounded-lg text-xs font-black uppercase border {{ $color }}">
        {{ $venta->estado ?? 'pendiente' }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Columna Info General --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Tarjeta Cliente --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">person</span> Cliente
            </h3>
            @if($venta->user)
                <p class="font-bold text-slate-900 text-lg">{{ $venta->user->nombre }} {{ $venta->user->apellido }}</p>
                <p class="text-sm text-slate-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">mail</span> {{ $venta->user->email }}</p>
                @if($venta->user->telefono)
                    <p class="text-sm text-slate-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">call</span> {{ $venta->user->telefono }}</p>
                @endif
            @else
                <p class="text-slate-500 italic">Usuario no registrado (Invitado)</p>
            @endif
        </div>

        {{-- Tarjeta Pago --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">payments</span> Detalles de Pago
            </h3>
            <p class="text-sm font-semibold capitalize text-slate-800 mb-2">{{ str_replace('_', ' ', $venta->metodo_pago) }}</p>
            
            @if($venta->banco_pago)
                <div class="text-sm text-slate-600 mb-1"><strong>Banco Emisor:</strong> {{ $venta->banco_pago }}</div>
            @endif
            @if($venta->telefono_pago)
                <div class="text-sm text-slate-600 mb-1"><strong>Teléfono Origen:</strong> {{ $venta->telefono_pago }}</div>
            @endif
            @if($venta->referencia_pago)
                <div class="text-sm text-slate-600"><strong>Referencia:</strong> <span class="font-mono bg-slate-100 px-1 py-0.5 rounded">{{ $venta->referencia_pago }}</span></div>
            @endif
        </div>

        {{-- Tarjeta Envío --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">local_shipping</span> Detalles de Entrega
            </h3>
            @if($venta->tipo_envio === 'retiro_tienda')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-sm font-black mb-2 border border-amber-200">
                    <span class="material-symbols-outlined text-[18px]">storefront</span> Retiro en Tienda
                </span>
                <p class="text-xs text-slate-500 mt-2 font-medium">El cliente pasará a retirar el pedido por el local.</p>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-black mb-3 border border-indigo-200">
                    <span class="material-symbols-outlined text-[18px]">two_wheeler</span> Delivery
                </span>
                <div class="text-sm text-slate-700 mb-1"><strong>Ciudad:</strong> {{ $venta->ciudad_envio }}, {{ $venta->estado_envio }}</div>
                <div class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 mt-2">
                    <strong>Dirección:</strong> <br>
                    {{ $venta->calle_envio }}
                </div>
            @endif
        </div>
        
    </div>

    {{-- Columna Carrito / Items --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-900">Artículos Comprados</h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @foreach($venta->detalles as $item)
                    <div class="p-6 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                            @if($item->variante && $item->variante->imagen)
                                <img src="{{ asset($item->variante->imagen) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900 text-sm">
                                @if($item->variante && $item->variante->producto)
                                <a href="{{ route('products.show', $item->variante->id) }}" class="hover:text-primary hover:underline transition-colors" target="_blank">
                                    {{ $item->variante->producto->nombre }}
                                </a>
                                @else
                                    Producto Eliminado
                                @endif
                                @if($item->variante && $item->variante->color) <span class="text-slate-400 font-normal">- {{ $item->variante->color }}</span> @endif
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Precio Unitario: {{ bs($item->precio_unitario, false, $venta->tasa_bcv_aplicada) }}</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-slate-900">x{{ $item->cantidad }}</p>
                            <p class="text-primary font-black mt-1">{{ bs($item->subtotal, false, $venta->tasa_bcv_aplicada) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-slate-500 font-medium">Total de la Orden</span>
                <span class="text-2xl font-black text-primary">{{ bs($venta->total_amount, false, $venta->tasa_bcv_aplicada) }} (Ref: ${{ number_format((float) $venta->total_amount, 2) }})</span>
            </div>
            
        </div>
        
        {{-- Panel de Acciones Finales --}}
        <div class="mt-6 flex flex-col md:flex-row justify-end items-center gap-3">
            @if($venta->estado === 'pendiente')
                <form action="{{ route('admin.orders.approve', $venta->id) }}" method="POST" onsubmit="return confirm('¿Confirmas que verificaste el abono de este pago en las cuentas de la empresa?')">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">price_check</span> Verificar Pago
                    </button>
                </form>
            @endif

            @if(!in_array($venta->estado, ['completado', 'entregado', 'cancelada', 'pendiente']))
                <form action="{{ route('admin.orders.deliver', $venta->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span> Marcar como Entregado
                    </button>
                </form>
            @endif

            @if(!in_array($venta->estado, ['cancelada']))
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.invoice', $venta->id) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">print</span> Imprimir / Descargar PDF
                    </a>
                    
                    @php
                        // Número fijado para la demostración en clase
                        $phone = '584245659154';

                        // Generación de URL Firmada
                        $invoiceUrl = \Illuminate\Support\Facades\URL::signedRoute('invoice.public', ['id' => $venta->id]);
                        // Magia para la presentación: Reemplaza localhost automático por la IP de la red WiFi en la que estés
                        $invoiceUrl = str_replace(['localhost', '127.0.0.1'], getHostByName(getHostName()), $invoiceUrl);
                        
                        $nombreCliente = $venta->user->nombre ?? 'Cliente';
                        $message = "¡Hola {$nombreCliente}! Confirmamos tu pago en Stitch & Co. Ya estamos preparando tu pedido. Puedes descargar tu factura oficial directamente en este enlace: " . $invoiceUrl;
                        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
                    @endphp
                    <a href="{{ $whatsappUrl }}" target="_blank" style="background-color: #22c55e;" class="text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-sm hover:opacity-90">
                        <span class="material-symbols-outlined text-[18px]">chat</span> Enviar por WhatsApp
                    </a>
                </div>
            @else
                <button disabled class="bg-slate-100 text-slate-400 px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 cursor-not-allowed border border-slate-200">
                    <span class="material-symbols-outlined text-[18px]">hourglass_empty</span> Pedido Cancelado (Sin Factura)
                </button>
            @endif
        </div>
    </div>
</div>

@endsection
