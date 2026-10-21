<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'areas' => 'Nombre',
            'categorias' => 'Nombre',
            'marcas' => 'Nombre',
            'modelos' => 'Nombre',
            'puestos' => 'Nombre',
            'tipos' => 'Nombre',
            'ubicaciones' => 'Nombre',
        ];

        foreach ($tables as $table => $afterColumn) {
            if (! Schema::hasColumn($table, 'Estatus')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($afterColumn) {
                    $tableBlueprint->string('Estatus', 20)->default('Activo')->after($afterColumn);
                });
            }

            if (! Schema::hasColumn($table, 'status')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($afterColumn) {
                    $tableBlueprint->enum('status', ['activo', 'inactivo'])->default('activo')->after($afterColumn);
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['areas', 'categorias', 'marcas', 'modelos', 'puestos', 'tipos', 'ubicaciones'] as $table) {
            if (Schema::hasColumn($table, 'status')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('status');
                });
            }

            if (Schema::hasColumn($table, 'Estatus')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropColumn('Estatus');
                });
            }
        }
    }
};
