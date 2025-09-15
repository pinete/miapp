<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    //use HasFactory;
    protected $table = 'proveedores';
    protected $fillable = ['nombre', 'cif', 'email', 'telefono']; // Campos que se pueden asignar masivamente
}
