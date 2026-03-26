<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = ['nombre', 'descripcion', 'categoria'];

    public function detalleProductos()
    {
        return $this->hasMany(DetalleProducto::class);
    }

    public function scopeByCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}
