<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function variantes()
    {
        return $this->hasMany(ProductoVariante::class, 'producto_id');
    }
}
