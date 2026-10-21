<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('Id_Usuario');
            $table->string('Nombre_Usuario', 50)->unique();
            $table->string('Password_Hash', 255);
            $table->foreignId('FkId_Rol')
                ->constrained('roles', 'Id_Rol')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Empleado')->nullable()
                ->constrained('empleados', 'Id_Empleado')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamp('Fecha_Creacion')->useCurrent();
            $table->dateTime('Ultimo_Acceso')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};