<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoVariante extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'producto_variantes';

    protected static function booted()
    {
        static::updated(function ($variante) {
            if ($variante->wasChanged('stock_base') && $variante->stock_base > 0) {
                $notificaciones = \App\Models\NotificacionCrm::where('variante_id', $variante->id)
                    ->whereIn('tipo', ['stock', 'stock_alert'])
                    ->where('procesado', false)
                    ->get();

                /** @var \App\Models\NotificacionCrm $notificacion */
                foreach ($notificaciones as $notificacion) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($notificacion->email)->send(new \App\Mail\BackInStockMail($variante));
                        $notificacion->update(['procesado' => true]);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Error sending BackInStock email to ' . $notificacion->email . ': ' . $e->getMessage());
                    }
                }
            }
        });
    }

    protected $fillable = [
        'producto_id',
        'parent_id',
        'proveedor_id',
        'color',
        'grosor',
        'marca',
        'unidad_medida',
        'factor_conversion',
        'stock_base',
        'precio',
        'precio_usd',
        'imagen',
        'en_oferta',
        'descuento_porcentaje'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function parent()
    {
        return $this->belongsTo(ProductoVariante::class, 'parent_id');
    }

    public function empaques()
    {
        return $this->hasMany(ProductoVariante::class, 'parent_id');
    }

    public function inventarioLogs()
    {
        return $this->hasMany(InventarioLog::class, 'variante_id');
    }

    // Accessor para obtener el stock calculado basado en la unidad base
    public function getStockDisponibleAttribute()
    {
        if (is_null($this->parent_id)) {
            return $this->stock_base;
        }

        if ($this->parent && $this->parent->stock_base > 0 && $this->factor_conversion > 0) {
            return floor($this->parent->stock_base / $this->factor_conversion);
        }

        return 0;
    }
}
