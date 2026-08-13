@extends('layouts.admin')

@section('title', 'Reportes Gerenciales')

@section('content')
<section class="space-y-8">
    {{-- Header con gradiente premium --}}
    <div class="rounded-2xl shadow-lg p-8 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #311042 50%, #1e1b4b 100%);">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-2xl font-black tracking-tight flex items-center gap-3">
                    <span class="material-symbols-outlined text-3xl text-purple-400">bar_chart</span>
                    Centro de Reportes Gerenciales
                </h2>
                <p class="text-slate-300 mt-1 max-w-xl text-sm leading-relaxed">
                    Monitorea el rendimiento comercial, la valoración de tu stock y la actividad de tus clientes mediante informes detallados en tiempo real.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.reportes.pdf', ['tipo' => $tipo, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]) }}" 
                   target="_blank"
                   class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-md flex items-center gap-2 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    Descargar Reporte PDF
                </a>
            </div>
        </div>
        {{-- Adorno abstracto de fondo --}}
        <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-purple-500 rounded-full opacity-10 blur-3xl pointer-events-none"></div>
    </div>

    {{-- Formulario de Selección y Filtros --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="GET" action="{{ route('admin.reportes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            {{-- Tipo de Reporte --}}
            <div class="md:col-span-2">
                <label for="tipo" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Seleccione Tipo de Reporte</label>
                <div class="grid grid-cols-3 gap-2 bg-slate-100 p-1 rounded-xl">
                    <a href="{{ route('admin.reportes.index', ['tipo' => 'ventas', 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]) }}"
                       class="text-center py-2.5 rounded-lg text-xs font-bold transition-all {{ $tipo === 'ventas' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Ventas y Finanzas
                    </a>
                    <a href="{{ route('admin.reportes.index', ['tipo' => 'inventario', 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]) }}"
                       class="text-center py-2.5 rounded-lg text-xs font-bold transition-all {{ $tipo === 'inventario' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Inventario
                    </a>
                    <a href="{{ route('admin.reportes.index', ['tipo' => 'clientes', 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]) }}"
                       class="text-center py-2.5 rounded-lg text-xs font-bold transition-all {{ $tipo === 'clientes' ? 'bg-white text-purple-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Clientes
                    </a>
                </div>
                <input type="hidden" name="tipo" value="{{ $tipo }}">
            </div>

            {{-- Filtros de Fecha (solo para Ventas y Clientes) --}}
            @if($tipo !== 'inventario')
                <div>
                    <label for="fecha_inicio" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fecha_inicio }}" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                </div>
                <div>
                    <label for="fecha_fin" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $fecha_fin }}" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                </div>
            @else
                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        <span class="font-bold text-slate-700">Tasa de Cambio Oficial (BCV):</span> Bs. {{ number_format($tasa_bcv, 2, ',', '.') }}
                    </div>
                    <div class="flex items-center gap-1 font-semibold text-emerald-600">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Tiempo real
                    </div>
                </div>
            @endif

            {{-- Botón Buscar si aplica --}}
            @if($tipo !== 'inventario')
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs flex items-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-sm">filter_alt</span>
                        Filtrar Reporte
                    </button>
                </div>
            @endif
        </form>
    </div>

    {{-- Métricas Clave (KPIs) --}}
    @if($tipo === 'ventas')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pedidos</h3>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($total_ventas) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">attach_money</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Facturado USD</h3>
                    <p class="text-2xl font-black text-slate-900">${{ number_format($total_ingresos_usd, 2) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <span class="material-symbols-outlined">currency_exchange</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Facturado Bs (BCV)</h3>
                    <p class="text-2xl font-black text-slate-900">Bs. {{ number_format($total_ingresos_bs, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ticket Promedio</h3>
                    <p class="text-2xl font-black text-slate-900">${{ number_format($promedio_ticket_usd, 2) }}</p>
                </div>
            </div>
        </div>
    @elseif($tipo === 'inventario')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined">category</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Productos Distintos</h3>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($total_productos) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined">grid_view</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Variantes</h3>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($total_variantes) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">monetization_on</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Valoración Almacén</h3>
                    <p class="text-xl font-black text-slate-900">${{ number_format($valoracion_total_usd, 2) }}</p>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Bs. {{ number_format($valoracion_total_bs, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                    <span class="material-symbols-outlined">warning_amber</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stock Crítico</h3>
                    <p class="text-2xl font-black text-red-600">{{ number_format($variantes_criticas) }}</p>
                </div>
            </div>
        </div>
    @elseif($tipo === 'clientes')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined">supervised_user_circle</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Clientes Activos</h3>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($total_clientes_activos) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Gasto Promedio por Cliente</h3>
                    <p class="text-2xl font-black text-slate-900">${{ number_format($promedio_gasto_cliente_usd, 2) }}</p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cliente Estrella</h3>
                    @if($cliente_estrella)
                        <p class="text-base font-black text-slate-900 truncate max-w-[200px]" title="{{ $cliente_estrella->name }}">
                            {{ $cliente_estrella->name }}
                        </p>
                        <span class="text-[10px] text-slate-500 font-bold">Total: ${{ number_format($cliente_estrella->total_gastado_usd, 2) }}</span>
                    @else
                        <p class="text-sm font-medium text-slate-400">Sin compras registradas</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Vista Previa de Tabla de Detalles --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Desglose Detallado del Informe</h3>
                <p class="text-slate-400 text-sm">Vista previa de los registros que formarán parte del reporte descargable</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($tipo === 'ventas')
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4">ID Pedido</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Método de Pago</th>
                            <th class="px-6 py-4">Estatus</th>
                            <th class="px-6 py-4 text-right">Total USD</th>
                            <th class="px-6 py-4 text-right">Total Bs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ordenes as $o)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-purple-600 font-bold">
                                    #{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $o->user->name ?? 'Invitado' }}</span>
                                    <span class="block text-xs text-slate-400">{{ $o->user->email ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $o->created_at->format('d/m/Y h:i A') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold uppercase bg-slate-100 text-slate-700 px-2 py-1 rounded">
                                        {{ str_replace('_', ' ', $o->metodo_pago) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($o->estado === 'completado' || $o->estado === 'entregado')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">Entregado</span>
                                    @elseif($o->estado === 'procesando' || $o->estado === 'pagado')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase">Pagado</span>
                                    @elseif($o->estado === 'pendiente')
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold uppercase">Pendiente</span>
                                    @elseif($o->estado === 'cancelada')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Cancelado</span>
                                    @else
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">{{ $o->estado }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                    ${{ number_format($o->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900 text-xs">
                                    Bs. {{ number_format($o->total_amount * ($o->tasa_bcv_aplicada ?? $tasa_bcv), 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    No se encontraron pedidos en el rango de fechas seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($tipo === 'inventario')
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4">Código</th>
                            <th class="px-6 py-4">Producto</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Proveedor</th>
                            <th class="px-6 py-4 text-center">Stock</th>
                            <th class="px-6 py-4 text-right">Precio Unitario</th>
                            <th class="px-6 py-4 text-right">Valoración (USD)</th>
                            <th class="px-6 py-4 text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($variantes as $v)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    VAR-{{ str_pad($v->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $v->producto->nombre ?? 'N/A' }}</span>
                                    <span class="block text-[10px] text-slate-400">
                                        Color: {{ $v->color ?? 'N/A' }} @if($v->talla) | Talla: {{ $v->talla }} @endif @if($v->grosor) | Grosor: {{ $v->grosor }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $v->producto->categoria->nombre ?? 'General' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $v->proveedor->nombre ?? 'Sin Asignar' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">
                                    {{ number_format($v->stock_base) }} <span class="text-[10px] font-normal text-slate-400">{{ $v->unidad_medida }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-600 font-semibold">
                                    ${{ number_format($v->precio, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                    ${{ number_format($v->stock_base * $v->precio, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($v->stock_base <= 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Sin Stock</span>
                                    @elseif($v->stock_base <= 5)
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-bold uppercase">Crítico</span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase">OK</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                    No se encontraron productos en el inventario.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($tipo === 'clientes')
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Documento</th>
                            <th class="px-6 py-4 text-center">Cant. Pedidos</th>
                            <th class="px-6 py-4 text-right">Total Gastado (USD)</th>
                            <th class="px-6 py-4 text-right">Total Gastado (Bs)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($clientes as $c)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    #{{ $c->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $c->name }}</span>
                                    <span class="block text-xs text-slate-400">{{ $c->email }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $c->tipo_documento ?? '' }}{{ $c->documento_identidad ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-800">
                                    {{ number_format($c->total_pedidos) }}
                                </td>
                                <td class="px-6 py-4 text-right text-emerald-600 font-bold">
                                    ${{ number_format($c->total_gastado_usd, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right text-slate-900 font-bold text-xs">
                                    Bs. {{ number_format($c->total_gastado_bs, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    No se encontraron clientes con compras en el rango de fechas seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</section>
@endsection
