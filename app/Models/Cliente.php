<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono']; // Campos que se pueden asignar masivamente

    // Relación polimórfica con adjuntos
    public function adjuntos() {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }
}
