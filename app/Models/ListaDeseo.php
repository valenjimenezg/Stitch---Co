<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaDeseo extends Model
{
    protected $table = 'lista_deseos';

    protected $fillable = ['user_id', 'variante_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variante()
    {
        return $this->belongsTo(DetalleProducto::class, 'variante_id');
    }
}
