<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionStock extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_stock';

    protected $fillable = [
        'email',
        'variante_id',
        'procesado',
    ];

    public function variante()
    {
        return $this->belongsTo(DetalleProducto::class, 'variante_id');
    }
}
