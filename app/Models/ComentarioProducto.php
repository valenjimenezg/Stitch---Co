<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioProducto extends Model
{
    use HasFactory;

    protected $table = 'comentarios_producto';

    protected $fillable = [
        'user_id',
        'producto_id',
        'titulo',
        'calificacion',
        'comentario',
        'aprobado',
        'verified_purchase',
        'respuesta_admin',
        'respondido_at',
    ];

    protected $casts = [
        'aprobado'          => 'boolean',
        'verified_purchase' => 'boolean',
        'respondido_at'     => 'datetime',
    ];

    // ------------------------------------
    // Scopes
    // ------------------------------------

    /** Solo trae comentarios aprobados por el admin */
    public function scopeAprobados($query)
    {
        return $query->where('aprobado', true);
    }

    /** Solo trae comentarios de compra verificada */
    public function scopeVerificados($query)
    {
        return $query->where('verified_purchase', true);
    }

    // ------------------------------------
    // Relaciones
    // ------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
