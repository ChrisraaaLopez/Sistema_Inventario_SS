<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['Id_Categoria' => 1, 'Nombre' => 'Mobiliario y Equipo de Administración', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 2, 'Nombre' => 'Equipo de Cómputo y de Tecnologías de la Información', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 3, 'Nombre' => 'Mobiliario y Equipo Educacional y Recreativo', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 4, 'Nombre' => 'Equipo e Instrumental Médico y de Laboratorio', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 5, 'Nombre' => 'Maquinaria, Otros Equipos y Herramientas', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 6, 'Nombre' => 'Licencias Informáticas e Intelectuales', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 7, 'Nombre' => 'Vehículos y Equipo de Transporte', 'status' => 'activo', 'Estatus' => 'Activo'],
            ['Id_Categoria' => 8, 'Nombre' => 'Equipo de Defensa y Seguridad', 'status' => 'activo', 'Estatus' => 'Activo'],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->updateOrInsert(['Id_Categoria' => $categoria['Id_Categoria']], $categoria);
            DB::table('categorias')->where('Id_Categoria', $categoria['Id_Categoria'])->update(['status' => 'activo', 'Estatus' => 'Activo']);
        }
    }
}