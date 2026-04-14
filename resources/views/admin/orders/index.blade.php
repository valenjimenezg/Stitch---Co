@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Gestión de Pedidos</h2>
    <div class="flex items-center gap-3">
        @if(\App\Models\Orden::where('estado', 'cancelada')->count() > 0)
        <form method="POST" action="{{ route('admin.orders.destroy_cancelled') }}" class="m-0" onsubmit="return confirm('¿Estás SEGURO de que deseas eliminar TODOS los pedidos cancelados? Esta acción masiva ocultará los registros del panel definitivamente.');">
            @csrf @method('DELETE')
            <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold border border-red-200 hover:bg-red-100 hover:text-red-700 transition-all flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                Borrar todas las que son canceladas
            </button>
        </form>
        @endif
        <a href="{{ route('admin.orders.export') }}" class="bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-lg">download</span>
            Exportar CSV
        </a>
    </div>
</div>



<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">N° Pedido</th>
                    <th class="px-6 py-4">Cliente</th>
                    <th class="px-6 py-4">Fecha</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Pago / Ref</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($ventas as $venta)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-primary font-semibold">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold">{{ $venta->user->nombre ?? '—' }} {{ $venta->user->apellido ?? '' }}</div>
                        <div class="text-xs text-slate-400">{{ $venta->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-semibold whitespace-nowrap">
                        <span class="block">{{ bs($venta->total_amount, false, $venta->tasa_bcv_aplicada) }}</span>
                        <span class="text-[10px] text-slate-400 font-bold block mt-0.5 uppercase">Ref: ${{ number_format($venta->total_amount, 2) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-xs uppercase text-slate-600">{{ str_replace('_', ' ', $venta->metodo_pago) }}</div>
                        @if($venta->referencia_pago)
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                Ref: <span class="font-mono text-slate-700">{{ $venta->referencia_pago }}</span>
                            </div>
                        @endif
                        @if($venta->banco_pago)
                            <div class="text-[10px] text-slate-400">
                                {{ $venta->banco_pago }}
                                @if($venta->telefono_pago)
                                    <span>({{ $venta->telefono_pago }})</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $colores = [
                                'pendiente'   => 'bg-amber-100 text-amber-700',
                                'procesando'  => 'bg-blue-100 text-blue-700',
                                'enviado'     => 'bg-indigo-100 text-indigo-700',
                                'entregado'   => 'bg-emerald-100 text-emerald-700',
                                'cancelada'   => 'bg-red-100 text-red-700',
                            ];
                            $color = $colores[$venta->estado] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $color }}">
                            {{ $venta->estado ?? 'pendiente' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <form method="POST" action="{{ route('admin.orders.status', $venta->id) }}" class="w-[115px] shrink-0 m-0">
                                @csrf @method('PATCH')
                                <select name="estado" onchange="this.form.submit()" class="w-full border-slate-200 rounded-lg text-xs py-1.5 pl-3 pr-8 focus:ring-primary focus:border-primary cursor-pointer bg-slate-50 hover:bg-white transition-colors" title="Cambiar Estado">
                                    @foreach(['pendiente', 'procesando', 'enviado', 'entregado', 'cancelada'] as $estado)
                                        <option value="{{ $estado }}" {{ $venta->estado === $estado ? 'selected' : '' }}>
                                            {{ ucfirst($estado) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            
                            <div class="flex items-center gap-1.5 w-[110px] shrink-0 justify-start">
                                <a href="{{ route('admin.orders.show', $venta->id) }}" class="text-slate-500 hover:text-primary p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors bg-white shadow-sm flex items-center justify-center h-[34px] w-[34px] shrink-0" title="Vista Detallada del Pedido">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>

                                <a href="{{ route('admin.orders.generate_invoice', $venta->id) }}" target="_blank" class="text-blue-600 hover:text-white hover:bg-blue-600 p-1.5 rounded-lg border border-blue-200 transition-colors bg-blue-50 shadow-sm flex items-center justify-center h-[34px] w-[34px] shrink-0" title="Imprimir Factura Fiscal">
                                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                </a>

                                <div class="w-[34px] shrink-0">
                                    @if($venta->estado !== 'cancelada' && $venta->estado !== 'entregado')
                                        <form method="POST" action="{{ route('admin.orders.status', $venta->id) }}" class="m-0" onsubmit="return confirm('¿Confirmas que deseas cancelar este pedido? Se restaurará el stock al inventario de manera automática.');">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="estado" value="cancelada">
                                            <button type="submit" class="text-rose-600 hover:text-white hover:bg-rose-600 p-1.5 rounded-lg border border-rose-200 transition-colors bg-rose-50 shadow-sm flex items-center justify-center h-[34px] w-[34px]" title="Anular y Cancelar Pedido">
                                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                            </button>
                                        </form>
                                    @elseif($venta->estado === 'cancelada' || ($venta->estado === 'pendiente' && $venta->created_at->diffInHours(now()) > 48))
                                        <form method="POST" action="{{ route('admin.orders.destroy', $venta->id) }}" class="m-0" onsubmit="return confirm('ATENCIÓN: ¿Estás seguro de que deseas eliminar permanentemente este registro del ERP?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-white hover:bg-slate-800 p-1.5 rounded-lg border border-slate-200 transition-colors bg-slate-50 shadow-sm flex items-center justify-center h-[34px] w-[34px]" title="Eliminar Registro de la Base de Datos">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Botones de Logística y Entrega --}}
                            <div class="w-[140px] shrink-0 flex justify-start pl-3 border-l border-slate-100">
                                @if($venta->estado !== 'entregado' && $venta->estado !== 'cancelada')
                                    @if($venta->delivery_method === 'store_pickup')
                                        <form method="POST" action="{{ route('admin.orders.picked_up', $venta->id) }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 text-white hover:bg-emerald-700 px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap shadow-sm transition-colors flex items-center gap-1" title="Formalizar Entrega Física">
                                                <span class="material-symbols-outlined text-[14px]">storefront</span>
                                                Entregado
                                            </button>
                                        </form>
                                    @elseif($venta->delivery_method === 'local_delivery')
                                        <form method="POST" action="{{ route('admin.orders.delivered_locally', $venta->id) }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap shadow-sm transition-colors flex items-center gap-1" title="Confirmar Recepción por Motorizado">
                                                <span class="material-symbols-outlined text-[14px]">local_shipping</span>
                                                Entregado
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        No hay pedidos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ventas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $ventas->links() }}
        </div>
    @endif
</div>

@endsection
