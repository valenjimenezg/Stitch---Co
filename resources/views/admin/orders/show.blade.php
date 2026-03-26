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
            'cancelado'   => 'bg-red-100 text-red-700 border-red-200',
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
                                <img src="{{ Storage::url($item->variante->imagen) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900 text-sm">
                                {{ $item->variante->producto->nombre ?? 'Producto Eliminado' }}
                                @if($item->variante && $item->variante->color) <span class="text-slate-400 font-normal">- {{ $item->variante->color }}</span> @endif
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">Precio Unitario: Bs. {{ number_format($item->precio_unitario, 2) }}</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-bold text-slate-900">x{{ $item->cantidad }}</p>
                            <p class="text-primary font-black mt-1">Bs. {{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-slate-500 font-medium">Total de la Orden</span>
                <span class="text-2xl font-black text-primary">Bs. {{ number_format((float) $venta->total_venta, 2) }}</span>
            </div>
            
        </div>
        
        {{-- Panel de Acciones Finales --}}
        <div class="mt-6 flex justify-end gap-3">
            @if($venta->factura)
                <a href="{{ route('admin.orders.invoice', $venta->id) }}" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span> Factura #{{ $venta->factura->id }}
                </a>
            @endif
        </div>
    </div>
</div>

@endsection
