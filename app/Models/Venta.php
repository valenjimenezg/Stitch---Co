<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;
    protected $table = 'ventas';

    protected $fillable = [
        'user_id', 'total_venta', 'subtotal', 'iva_amount', 'delivery_fee', 'total_amount', 'metodo_pago', 'estado',
        'referencia_pago', 'banco_pago', 'telefono_pago',
        'tipo_envio', 'costo_envio', 'agencia_envio',
        'calle_envio', 'ciudad_envio', 'estado_envio', 'codigo_postal_envio', 'tasa_bcv_aplicada',
        'delivery_method', 'invoice_number', 'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'total_venta' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'iva_amount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'completed_at' => 'datetime'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
