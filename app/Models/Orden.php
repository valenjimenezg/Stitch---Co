<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property int         $user_id
 * @property string      $estado
 * @property float       $subtotal
 * @property float       $iva_amount
 * @property float       $delivery_fee
 * @property float       $total_amount
 * @property float       $monto_abonado
 * @property float|null  $tasa_bcv_aplicada
 * @property array|null  $direccion_envio
 * @property string      $tipo_envio
 * @property string|null $agencia_envio
 * @property string      $metodo_pago
 * @property string|null $referencia_pago
 * @property string|null $banco_pago
 * @property string|null $telefono_pago
 * @property string|null $invoice_number
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon      $created_at
 * @property \Illuminate\Support\Carbon      $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
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

    public function getCalleEnvioAttribute()
    {
        return $this->direccion_envio['calle'] ?? null;
    }

    public function getCiudadEnvioAttribute()
    {
        return $this->direccion_envio['ciudad'] ?? null;
    }

    public function getEstadoEnvioAttribute()
    {
        return $this->direccion_envio['estado'] ?? null;
    }

    public function getCodigoPostalEnvioAttribute()
    {
        return $this->direccion_envio['codigo_postal'] ?? null;
    }
}
