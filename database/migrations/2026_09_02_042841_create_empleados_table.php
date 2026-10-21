<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('Id_Empleado');
            $table->string('N_Trabajador', 10)->unique();
            $table->string('Nombre', 50);
            $table->string('Apellido_Paterno', 50);
            $table->string('Apellido_Materno', 50)->nullable();
            $table->foreignId('FkId_Puesto')
                ->constrained('puestos', 'Id_Puesto')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Area')
                ->constrained('areas', 'Id_Area')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('Estatus', ['Activo', 'Inactivo', 'Baja'])->default('Activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};