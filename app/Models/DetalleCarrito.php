<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCarrito extends Model
{
    protected $table = 'detalle_carritos';

    protected $fillable = ['carrito_id', 'variante_id', 'cantidad'];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }

    public function variante()
    {
        return $this->belongsTo(DetalleProducto::class, 'variante_id');
    }
}
