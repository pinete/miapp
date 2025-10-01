<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adjuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('mime');
            $table->longBlob('contenido'); // Aquí va el archivo. Si usas MySQL y esperas archivos grandes, puedes usar longBlob() en lugar de binary().
            $table->morphs('adjuntable'); // entidad_id + entidad_type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjuntos');
    }
};
