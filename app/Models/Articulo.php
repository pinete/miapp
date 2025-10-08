<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'pvp',
        'tipoImp',
        'porcImp',
        'ctrlSerLot',
        'ctrlStock',
        'stockMin',
        'stockMax',
        'numDecimales',
    ];

    // Relación polimórfica con adjuntos
    public function adjuntos() {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }
}
