<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orden extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordenes';

    protected $fillable = [
        'user_id',
        'estado',
        'subtotal',
        'iva_amount',
        'delivery_fee',
        'total_amount',
        'monto_abonado',
        'tasa_bcv_aplicada',
        'direccion_envio',
        'tipo_envio',
        'agencia_envio',
        'metodo_pago',
        'referencia_pago',
        'banco_pago',
        'telefono_pago',
        'invoice_number',
        'completed_at'
    ];

    protected $casts = [
        'direccion_envio' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(OrdenDetalle::class, 'orden_id');
    }

    public function inventarioLogs()
    {
        return $this->hasMany(InventarioLog::class, 'orden_id');
    }

    public function recalcularTotales()
    {
        $subtotal = $this->detalles()->sum('subtotal');
        // Let's assume standard parameters:
        $iva = round($subtotal * 0.16, 2);
        // Delivery fee assuming it remains the same as previously configured or 0
        $delivery_fee = $this->delivery_fee ?? 0;
        
        $this->update([
            'subtotal' => $subtotal,
            'iva_amount' => $iva,
            'total_amount' => $subtotal + $iva + $delivery_fee
        ]);
        
        return $this;
    }
}
