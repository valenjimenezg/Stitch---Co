<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description', 'stock_total_base', 'imagen',
        'grosor', 'color', 'marca', 'cm', 'unidad_medida', 'en_oferta', 'descuento_porcentaje'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function presentations()
    {
        return $this->hasMany(ProductPresentation::class);
    }
}
