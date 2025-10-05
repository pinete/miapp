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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->float('pvp')->nullable()->default(0);
            $table->integer('tipoImp')->default(1); // Tipo de impuesto: IVA, IGI,...por defecto 1 representa a 'IVA'
            $table->float('porcImp')->default(21); // Porcentaje de impuesto. 21% por defecto
            $table->boolean('ctrlSerLot')->default(false);// Control de numeros de serie/lote (Si/No)
            $table->boolean('ctrlStock')->default(false); // Control de stock (Si/No)
            $table->float('stockMin')->nullable(); // Cantidad mínima de stock
            $table->float('stockMax')->nullable(); // Cantidad máxima de stock
            $table->integer('numDecimales')->nullable(); // permite NULL
            $table->timestamps(); //Fecha de creación y modificación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
