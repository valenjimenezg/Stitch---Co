<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $fillable = ['variante_id', 'venta_id', 'cantidad', 'tipo', 'motivo'];

    public function variante() { return $this->belongsTo(DetalleProducto::class, 'variante_id'); }
    public function venta() { return $this->belongsTo(Venta::class); }
}
