<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDetalle extends Model
{
    use HasFactory;

    protected $table = 'orden_detalles';

    protected $fillable = [
        'orden_id',
        'variante_id',
        'cantidad',
        'precio_unitario',
        'unidad_medida_snapshot',
        'factor_conversion_snapshot',
        'subtotal'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'variante_id');
    }
}
