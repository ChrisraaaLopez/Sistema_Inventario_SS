<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'roles',
            'areas',
            'categorias',
            'puestos',
            'marcas',
            'ubicaciones',
            'tipos',
            'empleados',
            'usuarios',
            'modelos',
            'facturas',
            'articulos',
            'articulos_bajas',
            'periodos',
            'resguardos',
            'resguardo_articulos',
        ];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (! Schema::hasColumn($tableName, 'status')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->enum('status', ['activo', 'inactivo'])->default('activo');
                });
            }

            if (! Schema::hasColumn($tableName, 'Estatus')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->enum('Estatus', ['Activo', 'Inactivo'])->default('Activo');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'roles',
            'areas',
            'categorias',
            'puestos',
            'marcas',
            'ubicaciones',
            'tipos',
            'empleados',
            'usuarios',
            'modelos',
            'facturas',
            'articulos',
            'articulos_bajas',
            'periodos',
            'resguardos',
            'resguardo_articulos',
        ];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'status')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }

            if (Schema::hasColumn($tableName, 'Estatus')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('Estatus');
                });
            }
        }
    }
};
