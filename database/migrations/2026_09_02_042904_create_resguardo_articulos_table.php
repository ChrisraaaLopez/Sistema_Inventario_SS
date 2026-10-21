<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resguardo_articulos', function (Blueprint $table) {
            $table->id('Id_Resguardo_Articulo');
            $table->foreignId('FkId_Resguardo')
                ->constrained('resguardos', 'Id_Resguardo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Articulo')
                ->constrained('articulos', 'Id_Articulo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Ubicacion')
                ->constrained('ubicaciones', 'Id_Ubicacion')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('Revisado')->default(false);
            $table->dateTime('Fecha_Revision')->nullable();
            $table->string('Notas', 200)->nullable();
            $table->string('Comentarios', 500)->nullable();
            $table->unique(['FkId_Resguardo', 'FkId_Articulo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resguardo_articulos');
    }
};