<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->id('Id_Marca');
            $table->string('Nombre', 100);
            $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marcas');
    }
};