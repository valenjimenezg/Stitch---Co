<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = ['user_id', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCarrito::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->detalles->sum(function ($detalle) {
            return $detalle->variante->precio_con_descuento * $detalle->cantidad;
        });
    }
}
