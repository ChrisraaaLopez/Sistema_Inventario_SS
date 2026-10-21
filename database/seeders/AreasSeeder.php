<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['Id_Area' => 1, 'Nombre' => 'Dirección', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Area' => 2, 'Nombre' => 'Administración', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Area' => 3, 'Nombre' => 'Académica', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Area' => 4, 'Nombre' => 'Planeación y Vinculación', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Area' => 5, 'Nombre' => 'Control Escolar', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Area' => 6, 'Nombre' => 'TICS', 'status' => 'activo', 'Estatus' => 'Activo'],
        ];

        foreach ($areas as $area) {
            DB::table('areas')->updateOrInsert(['Id_Area' => $area['Id_Area']], $area);
            DB::table('areas')->where('Id_Area', $area['Id_Area'])->update(['status' => 'activo', 'Estatus' => 'Activo']);
        }
    }
}