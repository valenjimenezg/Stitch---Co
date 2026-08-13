<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte Oficial - Stitch & Co.</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 10px 20px; 
            color: #1e293b; 
            font-size: 11px; 
            line-height: 1.4;
        }
        .header { 
            width: 100%; 
            border-bottom: 3px solid #8b52ff; 
            padding-bottom: 12px; 
            margin-bottom: 20px; 
        }
        .header table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .brand h1 { 
            margin: 0; 
            font-size: 22px; 
            color: #8b52ff; 
            font-weight: bold;
        }
        .brand p { 
            margin: 3px 0 0 0; 
            font-size: 10px; 
            color: #64748b; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .meta { 
            text-align: right; 
            font-size: 10px; 
            color: #64748b; 
        }
        .meta strong { 
            color: #0f172a; 
        }
        
        /* KPIs Table layout for DomPDF compatibility */
        .kpis-container { 
            width: 100%; 
            margin-bottom: 25px; 
        }
        .kpis-table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 10px 0; 
        }
        .kpi-card { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0; 
            padding: 12px 15px; 
            border-radius: 8px; 
            text-align: left;
        }
        .kpi-title { 
            font-size: 9px; 
            font-weight: bold; 
            color: #64748b; 
            text-transform: uppercase; 
            margin-bottom: 4px;
        }
        .kpi-value { 
            font-size: 18px; 
            font-weight: 900; 
            color: #0f172a; 
        }
        .kpi-sub { 
            font-size: 9px; 
            color: #94a3b8; 
            margin-top: 2px; 
        }

        /* Detail Table */
        .table-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        table.items { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        table.items th { 
            text-align: left; 
            padding: 8px 10px; 
            background: #f1f5f9; 
            border-bottom: 2px solid #cbd5e1; 
            font-size: 9px; 
            text-transform: uppercase; 
            color: #475569; 
            font-weight: bold;
        }
        table.items td { 
            padding: 8px 10px; 
            border-bottom: 1px solid #e2e8f0; 
            font-size: 10px; 
            vertical-align: middle;
        }
        table.items tr:nth-child(even) {
            background: #f8fafc;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            text-align: center;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-info { background: #dbeafe; color: #1d4ed8; }
        .badge-neutral { background: #f1f5f9; color: #475569; }

        .footer { 
            margin-top: 40px; 
            text-align: center; 
            color: #94a3b8; 
            font-size: 8px; 
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="brand" style="width: 60%;">
                    <h1>Stitch & Co.</h1>
                    <p>Reporte Oficial de {{ ucfirst($tipo === 'ventas' ? 'Ventas y Finanzas' : ($tipo === 'inventario' ? 'Inventario' : 'Actividad de Clientes')) }}</p>
                </td>
                <td class="meta" style="width: 40%;">
                    <p>Fecha Emisión: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</strong></p>
                    <p>Rango: <strong>
                        @if($tipo !== 'inventario')
                            {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                        @else
                            Tiempo Real (Tasa Bs. {{ number_format($tasa_bcv, 2, ',', '.') }})
                        @endif
                    </strong></p>
                </td>
            </tr>
        </table>
    </div>

    {{-- KPIs Summary Block --}}
    <div class="kpis-container">
        @if($tipo === 'ventas')
            <table class="kpis-table">
                <tr>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Total Pedidos</div>
                            <div class="kpi-value">{{ number_format($total_ventas) }}</div>
                            <div class="kpi-sub">Transacciones registradas</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Ingresos USD</div>
                            <div class="kpi-value">${{ number_format($total_ingresos_usd, 2) }}</div>
                            <div class="kpi-sub">Excluye cancelados</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Ingresos Bs</div>
                            <div class="kpi-value" style="font-size: 13px; font-weight:900;">Bs. {{ number_format($total_ingresos_bs, 2, ',', '.') }}</div>
                            <div class="kpi-sub">A tasa BCV de compra</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Ticket Promedio</div>
                            <div class="kpi-value">${{ number_format($promedio_ticket_usd, 2) }}</div>
                            <div class="kpi-sub">Por pedido exitoso</div>
                        </div>
                    </td>
                </tr>
            </table>
        @elseif($tipo === 'inventario')
            <table class="kpis-table">
                <tr>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Productos Base</div>
                            <div class="kpi-value">{{ number_format($total_productos) }}</div>
                            <div class="kpi-sub">Modelos en catálogo</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Variantes Únicas</div>
                            <div class="kpi-value">{{ number_format($total_variantes) }}</div>
                            <div class="kpi-sub">Talla / Color / Stock</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Valoración USD</div>
                            <div class="kpi-value">${{ number_format($valoracion_total_usd, 2) }}</div>
                            <div class="kpi-sub">Bs. {{ number_format($valoracion_total_bs, 2, ',', '.') }}</div>
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Riesgo Crítico</div>
                            <div class="kpi-value" style="color: #b91c1c;">{{ number_format($variantes_criticas) }}</div>
                            <div class="kpi-sub">Variantes stock <= 5</div>
                        </div>
                    </td>
                </tr>
            </table>
        @elseif($tipo === 'clientes')
            <table class="kpis-table">
                <tr>
                    <td style="width: 33%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Clientes Activos</div>
                            <div class="kpi-value">{{ number_format($total_clientes_activos) }}</div>
                            <div class="kpi-sub">Con compras registradas</div>
                        </div>
                    </td>
                    <td style="width: 33%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Gasto Promedio</div>
                            <div class="kpi-value">${{ number_format($promedio_gasto_cliente_usd, 2) }}</div>
                            <div class="kpi-sub">Por cliente activo</div>
                        </div>
                    </td>
                    <td style="width: 34%;">
                        <div class="kpi-card">
                            <div class="kpi-title">Cliente Estrella</div>
                            <div class="kpi-value" style="font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $cliente_estrella ? $cliente_estrella->name : 'N/A' }}
                            </div>
                            <div class="kpi-sub">Gasto: ${{ $cliente_estrella ? number_format($cliente_estrella->total_gastado_usd, 2) : '0' }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        @endif
    </div>

    {{-- Detail Table --}}
    <div class="table-title">Desglose Detallado del Informe</div>

    @if($tipo === 'ventas')
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 12%;">Pedido ID</th>
                    <th style="width: 25%;">Cliente / Email</th>
                    <th style="width: 18%;">Fecha</th>
                    <th style="width: 15%;">Método Pago</th>
                    <th style="width: 10%;">Estatus</th>
                    <th style="width: 10%; text-align: right;">Total USD</th>
                    <th style="width: 10%; text-align: right;">Total Bs</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordenes as $o)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold; color: #8b52ff;">#{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $o->user->name ?? 'Invitado' }}</strong><br>
                            <span style="font-size: 8px; color: #64748b;">{{ $o->user->email ?? '' }}</span>
                        </td>
                        <td>{{ $o->created_at->format('d/m/Y h:i A') }}</td>
                        <td><span class="badge badge-neutral">{{ str_replace('_', ' ', $o->metodo_pago) }}</span></td>
                        <td>
                            @if($o->estado === 'completado' || $o->estado === 'entregado')
                                <span class="badge badge-success">Entregado</span>
                            @elseif($o->estado === 'procesando' || $o->estado === 'pagado')
                                <span class="badge badge-info">Pagado</span>
                            @elseif($o->estado === 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif($o->estado === 'cancelada')
                                <span class="badge badge-danger">Cancelado</span>
                            @else
                                <span class="badge badge-neutral">{{ $o->estado }}</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: bold;">${{ number_format($o->total_amount, 2) }}</td>
                        <td style="text-align: right; font-weight: bold;">Bs. {{ number_format($o->total_amount * ($o->tasa_bcv_aplicada ?? $tasa_bcv), 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">No hay registros en el rango de fechas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($tipo === 'inventario')
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 12%;">Código</th>
                    <th style="width: 25%;">Producto / Especificación</th>
                    <th style="width: 15%;">Categoría</th>
                    <th style="width: 15%;">Proveedor</th>
                    <th style="width: 8%; text-align: center;">Stock</th>
                    <th style="width: 10%; text-align: right;">Costo USD</th>
                    <th style="width: 10%; text-align: right;">Val. USD</th>
                    <th style="width: 10%;">Estatus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variantes as $v)
                    <tr>
                        <td style="font-family: monospace; color: #64748b;">VAR-{{ str_pad($v->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $v->producto->nombre ?? 'N/A' }}</strong><br>
                            <span style="font-size: 8px; color: #64748b;">
                                Color: {{ $v->color ?? 'N/A' }} @if($v->talla) | Talla: {{ $v->talla }} @endif
                            </span>
                        </td>
                        <td>{{ $v->producto->categoria->nombre ?? 'General' }}</td>
                        <td>{{ $v->proveedor->nombre ?? 'Sin Asignar' }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ number_format($v->stock_base) }} <span style="font-size: 8px; font-weight: normal; color: #64748b;">{{ $v->unidad_medida }}</span></td>
                        <td style="text-align: right;">${{ number_format($v->precio, 2) }}</td>
                        <td style="text-align: right; font-weight: bold;">${{ number_format($v->stock_base * $v->precio, 2) }}</td>
                        <td>
                            @if($v->stock_base <= 0)
                                <span class="badge badge-danger">Sin Stock</span>
                            @elseif($v->stock_base <= 5)
                                <span class="badge badge-warning">Crítico</span>
                            @else
                                <span class="badge badge-success">OK</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">No hay productos en inventario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($tipo === 'clientes')
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 30%;">Cliente / Email</th>
                    <th style="width: 18%;">Documento</th>
                    <th style="width: 12%; text-align: center;">Pedidos</th>
                    <th style="width: 15%; text-align: right;">Total (USD)</th>
                    <th style="width: 15%; text-align: right;">Total (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                    <tr>
                        <td style="font-family: monospace; color: #94a3b8;">#{{ $c->id }}</td>
                        <td>
                            <strong>{{ $c->name }}</strong><br>
                            <span style="font-size: 8px; color: #64748b;">{{ $c->email }}</span>
                        </td>
                        <td>{{ $c->tipo_documento ?? '' }}{{ $c->documento_identidad ?? 'N/A' }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ number_format($c->total_pedidos) }}</td>
                        <td style="text-align: right; font-weight: bold; color: #15803d;">${{ number_format($c->total_gastado_usd, 2) }}</td>
                        <td style="text-align: right; font-weight: bold;">Bs. {{ number_format($c->total_gastado_bs, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">No hay clientes registrados con compras.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Stitch & Co. © {{ date('Y') }} — Sistema de Control y Automatización Financiera Empresarial</p>
        <p>Este reporte se genera dinámicamente y está firmado digitalmente por la gerencia general de la marca.</p>
    </div>

</body>
</html>
