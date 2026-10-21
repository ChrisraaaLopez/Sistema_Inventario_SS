<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->id('Id_Periodo');
            $table->string('Nombre', 50);
            $table->date('Fecha_Inicio');
            $table->date('Fecha_Cierre')->nullable();
            $table->enum('Estado', ['Abierto', 'Cerrado'])->default('Abierto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};