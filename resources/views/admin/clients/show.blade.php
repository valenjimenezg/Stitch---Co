@extends('layouts.admin')

@section('title', 'Detalles del Cliente')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.clients') }}" class="text-slate-400 hover:text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Perfil: <span class="text-primary">{{ $cliente->nombre }} {{ $cliente->apellido }}</span></h2>
    </div>
    <a href="{{ route('admin.clients.edit', $cliente->id) }}" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md hover:bg-primary-dark transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">edit</span>
        Editar Perfil
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg flex items-center gap-2 font-bold">
        <span class="material-symbols-outlined text-[18px]">check_circle</span> {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="size-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-xl">
                    {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">{{ $cliente->nombre }} {{ $cliente->apellido }}</h3>
                    <p class="text-slate-500 text-sm">Cliente desde {{ $cliente->created_at->format('M Y') }}</p>
                </div>
            </div>
            
            <ul class="space-y-4 text-sm">
                <li class="flex items-center gap-3 text-slate-700">
                    <span class="material-symbols-outlined text-slate-400">mail</span>
                    <a href="mailto:{{ $cliente->email }}" class="hover:text-primary transition-colors font-medium">{{ $cliente->email }}</a>
                </li>
                <li class="flex items-center gap-3 text-slate-700">
                    <span class="material-symbols-outlined text-slate-400">phone</span>
                    <span class="font-medium">{{ $cliente->telefono ?? 'Sin teléfono' }}</span>
                </li>
                <li class="flex items-center gap-3 text-slate-700">
                    <span class="material-symbols-outlined text-slate-400">badge</span>
                    <span class="font-medium">{{ $cliente->tipo_documento }}{{ $cliente->documento_identidad ?? 'Sin documento' }}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-8">
        <div>
            <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_bag</span> Pedidos
            </h3>
            
            @if($cliente->ordenes->count() > 0)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
                    @foreach($cliente->ordenes->whereNotIn('estado', ['carrito']) as $orden)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <a href="{{ route('admin.orders.show', $orden->id) }}" class="font-bold text-primary hover:text-primary-dark transition-colors">
                                    Pedido #{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}
                                </a>
                                <p class="text-xs text-slate-500 mt-1">{{ $orden->created_at->format('d M, Y H:i') }} &mdash; {{ bs($orden->total_amount) }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $orden->estado == 'entregado' ? 'bg-emerald-100 text-emerald-700' : 
                                      ($orden->estado == 'completado' ? 'bg-emerald-50 text-emerald-600' : 
                                      ($orden->estado == 'procesando' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600')) }}">
                                    {{ $orden->estado }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center text-slate-500 font-medium">
                    El cliente no ha realizado pedidos confirmados.
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500">notifications_active</span> Alertas de Reposición
            </h3>

            @if($notificaciones_stock->count() > 0)
                <div class="space-y-4">
                    @foreach($notificaciones_stock as $notificacion)
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center gap-4">
                        <div class="size-16 rounded-lg bg-slate-100 shrink-0 overflow-hidden border border-slate-200">
                            @if($notificacion->variante && $notificacion->variante->imagen)
                                <img src="{{ asset($notificacion->variante->imagen) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900 leading-tight mb-1">
                                {{ $notificacion->variante->producto->nombre ?? 'Producto Eliminado' }}
                            </h4>
                            <p class="text-xs text-slate-500 mb-2">
                                @if($notificacion->variante)
                                    Variante: <span class="font-semibold text-slate-700">{{ $notificacion->variante->color ?? $notificacion->variante->talla ?? 'Estándar' }}</span>
                                    @if($notificacion->variante->grosor) | {{ $notificacion->variante->grosor }} @endif
                                @endif
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold flex items-center gap-1 {{ $notificacion->procesado ? 'text-emerald-500' : 'text-amber-500' }}">
                                    @if($notificacion->procesado)
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Notificado
                                    @else
                                        <span class="material-symbols-outlined text-[14px]">schedule</span> En Espera
                                    @endif
                                </span>
                                <span class="text-xs text-slate-400">{{ $notificacion->created_at->format('d M, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center text-slate-500 font-medium">
                    El cliente no tiene alertas de reposición activas.
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">star</span> Reseñas de Productos
            </h3>

            @if($cliente->comentarios->count() > 0)
                <div class="space-y-4">
                    @foreach($cliente->comentarios as $review)
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <a href="{{ route('products.show', $review->producto->id) }}" target="_blank" class="font-bold text-slate-800 hover:text-primary transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">link</span>
                                {{ $review->producto->nombre }}
                            </a>
                            <span class="text-xs text-slate-400 font-medium">{{ $review->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1 mb-2 text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' {{ $i <= $review->calificacion ? '1' : '0' }}">{{ $i <= $review->calificacion ? 'star' : 'star' }}</span>
                            @endfor
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed italic">"{{ $review->comentario }}"</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center text-slate-500 font-medium">
                    El cliente no ha escrito reseñas.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
