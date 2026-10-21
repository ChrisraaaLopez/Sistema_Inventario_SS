<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos_bajas', function (Blueprint $table) {
            $table->id('Id_Baja');
            $table->foreignId('FkId_Articulo')->unique()
                ->constrained('articulos', 'Id_Articulo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('Fecha_Baja');
            $table->string('Motivo', 300)->nullable();
            $table->foreignId('FkId_Usuario')->nullable()
                ->constrained('usuarios', 'Id_Usuario')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos_bajas');
    }
};