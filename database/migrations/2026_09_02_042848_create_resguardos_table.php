<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resguardos', function (Blueprint $table) {
            $table->id('Id_Resguardo');
            $table->foreignId('FkId_Periodo')
                ->constrained('periodos', 'Id_Periodo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Empleado')
                ->constrained('empleados', 'Id_Empleado')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('Fecha_Firma')->nullable();
            $table->boolean('Cerrado')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resguardos');
    }
};