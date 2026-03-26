<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = ['venta_id', 'variante_id', 'cantidad', 'precio_unitario', 'subtotal'];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function variante()
    {
        return $this->belongsTo(DetalleProducto::class, 'variante_id');
    }
}
