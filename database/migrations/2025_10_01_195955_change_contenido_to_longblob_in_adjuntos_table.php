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
        /*
        Schema::table('adjuntos', function (Blueprint $table) {
            //$table->longBlob('contenido')->change();

        });
        */
        DB::statement('ALTER TABLE adjuntos MODIFY contenido LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        Schema::table('adjuntos', function (Blueprint $table) {
            //$table->binary('contenido')->change();
        });
        */
        DB::statement('ALTER TABLE adjuntos MODIFY contenido BLOB');
    }
};
