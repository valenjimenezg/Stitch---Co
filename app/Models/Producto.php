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
        'descripcion',
        'instrucciones_uso',
        'galeria'
    ];

    protected $casts = [
        'galeria' => 'array',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function variantes()
    {
        return $this->hasMany(ProductoVariante::class, 'producto_id');
    }

    public function comentarios()
    {
        return $this->hasMany(ComentarioProducto::class, 'producto_id');
    }
}
