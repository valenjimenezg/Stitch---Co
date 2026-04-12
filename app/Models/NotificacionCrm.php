<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionCrm extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_crm';

    protected $fillable = [
        'email',
        'tipo',
        'variante_id',
        'procesado'
    ];

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'variante_id');
    }
}
