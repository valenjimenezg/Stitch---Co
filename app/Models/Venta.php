<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'user_id', 'total_venta', 'metodo_pago', 'estado',
        'referencia_pago', 'banco_pago', 'telefono_pago',
        'tipo_envio', 'costo_envio', 'agencia_envio',
        'calle_envio', 'ciudad_envio', 'estado_envio', 'codigo_postal_envio'
    ];

    protected function casts(): array
    {
        return ['total_venta' => 'decimal:2'];
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
}
