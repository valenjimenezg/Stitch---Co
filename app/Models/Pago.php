<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    
    protected $fillable = [
        'venta_id',
        'payment_method',
        'bank_name',
        'reference_number',
        'receipt_path',
        'amount',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
