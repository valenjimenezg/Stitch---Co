<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioLog extends Model
{
    use HasFactory;

    protected $table = 'inventario_logs';

    protected $fillable = [
        'variante_id',
        'proveedor_id',
        'user_id',
        'cantidad_cambio',
        'motivo',
        'orden_id'
    ];

    public function variante()
    {
        return $this->belongsTo(ProductoVariante::class, 'variante_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }
}
