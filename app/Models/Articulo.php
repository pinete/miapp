<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    // Aunque en la migración tenga definidos campos boolean ...
    // Schema::getColumnType() no siempre detecta correctamente los campos boolean si están definidos como tinyint(1) 
    // en MySQL, lo cual es común. Laravel los interpreta como integer o tinyInteger.
    // Para evitar la confusión 1,0, como texto o numerico en lugar de boolean, protegemos y aseguramos con $casts

    protected $casts = [
        'ctrlSerLot' => 'boolean',
        'ctrlStock' => 'boolean',
    ];

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
