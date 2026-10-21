<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id('Id_Articulo');
            $table->string('Descripcion', 200);
            $table->foreignId('FkId_Marca')
                ->constrained('marcas', 'Id_Marca')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Modelo')
                ->constrained('modelos', 'Id_Modelo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('N_Serie', 30)->nullable();
            $table->string('Color', 100)->nullable();
            $table->foreignId('FkId_Categoria')
                ->constrained('categorias', 'Id_Categoria')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Tipo')
                ->constrained('tipos', 'Id_Tipo')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Ubicacion')
                ->constrained('ubicaciones', 'Id_Ubicacion')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Empleado')
                ->constrained('empleados', 'Id_Empleado')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('FkId_Factura')->nullable()
                ->constrained('facturas', 'Id_Factura')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->string('Notas', 200)->nullable();
            $table->string('Comentarios', 200)->nullable();
            $table->string('URL_Imagen', 255)->nullable();
            $table->enum('Estado', ['Bien', 'Reparacion', 'Dañado', 'Obsoleto'])->default('Bien');
            $table->enum('Tipo_Articulo', ['Capitalizable', 'No Capitalizable', 'Consumible', 'En Proceso de Baja', 'Baja']);
            $table->enum('status', ['activo', 'inactivo'])->default('activo');
            $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
            $table->boolean('Revisado')->default(false);
            $table->timestamp('Fecha_Creacion')->useCurrent();
            $table->timestamp('Fecha_Actualizacion')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};