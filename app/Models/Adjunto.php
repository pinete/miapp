<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    protected $fillable = ['nombre', 'mime', 'contenido'];
    public function adjuntable() {
        return $this->morphTo();
    }
}
