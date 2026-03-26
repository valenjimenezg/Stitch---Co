<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'monto',
        'fecha_emision'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
