<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id('Id_Modelo');
            $table->string('Nombre', 100);
            $table->foreignId('FkId_Marca')
                ->constrained('marcas', 'Id_Marca')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Tipo')
                ->constrained('tipos', 'Id_Tipo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('URL_Imagen', 200)->nullable();
            $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};