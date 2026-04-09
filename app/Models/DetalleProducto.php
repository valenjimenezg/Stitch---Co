<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleProducto extends Model
{
    protected $table = 'detalle_productos';

    protected $fillable = [
        'producto_id', 'color', 'grosor', 'cm', 'marca', 'unidad_medida',
        'factor_conversion', 'unidad_nombre',
        'precio_usd', 'precio', 'stock', 'imagen', 'en_oferta', 'descuento_porcentaje',
    ];

    protected function casts(): array
    {
        return [
            'precio_usd' => 'decimal:2',
            'precio' => 'decimal:2',
            'en_oferta' => 'boolean',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getPrecioConDescuentoAttribute(): float
    {
        if ($this->en_oferta && $this->descuento_porcentaje > 0) {
            return round($this->precio * (1 - $this->descuento_porcentaje / 100), 2);
        }
        return (float) $this->precio;
    }

    public function getEnStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getPrecioTotalPresentacionAttribute(): float
    {
        $factor = $this->factor_conversion ?: 1;
        return (float) ($this->precio * $factor);
    }
}
