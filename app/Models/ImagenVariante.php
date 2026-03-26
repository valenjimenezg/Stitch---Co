<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenVariante extends Model
{
    use HasFactory;

    protected $fillable = ['detalle_producto_id', 'ruta'];

    public function variante()
    {
        return $this->belongsTo(\App\Models\DetalleProducto::class, 'detalle_producto_id');
    }
}
