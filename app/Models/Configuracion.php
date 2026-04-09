<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = ['clave', 'valor', 'usar_tasa_manual', 'tasa_bcv_manual'];

    protected $casts = [
        'usar_tasa_manual' => 'boolean',
        'tasa_bcv_manual' => 'decimal:2',
    ];
}
