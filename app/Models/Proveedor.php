<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    //use HasFactory;
    protected $table = 'proveedores';
    protected $fillable = ['nombre', 'cif', 'email', 'telefono']; // Campos que se pueden asignar masivamente

    // Relación polimórfica con adjuntos
    public function adjuntos() {
        return $this->morphMany(Adjunto::class, 'adjuntable');
    }
}
