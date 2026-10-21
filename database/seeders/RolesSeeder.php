<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['Id_Rol' => 1, 'Nombre' => 'Administrador', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Rol' => 2, 'Nombre' => 'Revisor', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Rol' => 3, 'Nombre' => 'Consulta', 'status' => 'activo', 'Estatus' => 'Activo'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(['Id_Rol' => $rol['Id_Rol']], $rol);
            DB::table('roles')->where('Id_Rol', $rol['Id_Rol'])->update(['status' => 'activo', 'Estatus' => 'Activo']);
        }
    }
}