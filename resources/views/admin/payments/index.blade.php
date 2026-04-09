@extends('layouts.admin')

@section('title', 'Reconciliación de Pagos')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Conciliación de Pagos</h2>
        <p class="text-sm text-slate-500 mt-1">Órdenes pendientes por verificación manual de Pago Móvil o Transferencia.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($pagos as $pago)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
        {{-- Encabezado Header --}}
        <div class="p-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50">
            <div>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider mb-2 inline-block">
                    Por Verificar
                </span>
                <h3 class="font-bold text-slate-900 text-lg">${{ number_format($pago->total_amount, 2) }} <span class="text-xs font-normal text-slate-500">/ Bs {{ number_format($pago->total_amount * $pago->tasa_bcv_aplicada, 2) }}</span></h3>
                <p class="text-xs text-slate-500 mt-1">Orden #{{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">
                    {{ $pago->metodo_pago == 'pago_movil' ? 'send_to_mobile' : 'account_balance' }}
                </span>
            </div>
        </div>

        {{-- Desglose Financiero --}}
        <div class="px-5 border-b border-slate-100 pb-3 pt-3 bg-white">
            <div class="flex justify-between text-xs text-slate-600 mb-1">
                <span>Subtotal:</span> <span>${{ number_format($pago->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-xs text-slate-600 mb-1">
                <span>IVA (16%):</span> <span>${{ number_format($pago->iva_amount, 2) }}</span>
            </div>
            @if($pago->delivery_fee > 0)
            <div class="flex justify-between text-xs text-slate-600 mb-1">
                <span>Delivery:</span> <span class="text-emerald-600 font-bold">+${{ number_format($pago->delivery_fee, 2) }}</span>
            </div>
            @endif
        </div>

        {{-- Datos Bancarios --}}
        <div class="p-5 flex-1 space-y-4 pt-4">
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Referencia Bancaria</p>
                <div class="bg-slate-100 rounded-lg px-3 py-2 font-mono text-lg font-bold text-slate-800 flex items-center justify-between group cursor-copy" title="Copiar al portapapeles" onclick="navigator.clipboard.writeText('{{ $pago->referencia_pago }}'); alert('Referencia copiada.')">
                    {{ $pago->referencia_pago ?? 'No indicada' }}
                    <span class="material-symbols-outlined text-sm text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">content_copy</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Banco Origen</p>
                    <p class="text-sm font-medium text-slate-900">{{ strtoupper($pago->banco_pago ?? 'N/A') }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Teléfono</p>
                    <p class="text-sm font-medium text-slate-900">{{ $pago->telefono_pago ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Cliente</p>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-400 text-sm">person</span>
                    <span class="text-sm text-slate-700">{{ $pago->user->nombre ?? 'Invitado' }} {{ $pago->user->apellido ?? '' }}</span>
                </div>
                <div class="flex flex-col gap-1 w-full mt-1">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400 text-sm">schedule</span>
                        <span class="text-xs text-slate-500">{{ $pago->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @if($pago->pago && $pago->pago->receipt_path)
            <div class="pt-3 text-center">
                <a href="{{ Storage::url($pago->pago->receipt_path) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 w-full rounded-lg text-sm font-bold transition-colors">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span> Ver Comprobante
                </a>
            </div>
            @endif
        </div>

        {{-- Acciones Finales --}}
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
            <a href="{{ route('admin.orders.show', $pago->id) }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">visibility</span> Ver
            </a>
            <form action="{{ route('admin.orders.status', $pago->id) }}" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="cancelado">
                <button type="submit" class="w-full flex justify-center items-center gap-1.5 px-3 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-sm font-bold transition-colors" onclick="return confirm('¿Rechazar este pago e invalidar orden?')">
                    <span class="material-symbols-outlined text-[18px]">cancel</span> Rechazar
                </button>
            </form>
            
            <form action="{{ route('admin.orders.status', $pago->id) }}" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="procesando">
                <button type="submit" class="w-full flex justify-center items-center gap-1.5 px-3 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold hover:bg-emerald-600 transition-colors shadow-sm shadow-emerald-500/20" onclick="return confirm('¿Confirmas que el dinero está en la cuenta?')">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span> Aprobar
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 px-6 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="size-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-emerald-500">task_alt</span>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">¡Todo al día!</h3>
        <p class="text-slate-500 max-w-sm mx-auto">No hay transferencias ni pagos móviles pendientes por conciliar. Has verificado todos los pedidos entrantes.</p>
    </div>
    @endforelse
</div>

@if($pagos->hasPages())
<div class="mt-8">
    {{ $pagos->links() }}
</div>
@endif

@endsection
