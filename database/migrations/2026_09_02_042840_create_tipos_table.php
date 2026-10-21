<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $table) {
            $table->id('Id_Tipo');
            $table->string('Nombre', 200);
            $table->foreignId('FkId_Categoria')
                ->constrained('categorias', 'Id_Categoria')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};