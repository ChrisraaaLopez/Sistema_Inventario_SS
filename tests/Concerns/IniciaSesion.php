<?php

namespace Tests\Concerns;

use App\Models\Rol;
use App\Models\Usuario;
use Database\Seeders\AreasSeeder;
use Database\Seeders\CategoriasSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\DB;

/**
 * Ayudantes para las pruebas: inicia sesión con un rol y crea datos base del inventario.
 */
trait IniciaSesion
{
    protected function sembrarCatalogosFijos(): void
    {
        if (!Rol::where('Nombre', 'Administrador')->exists()) {
            $this->seed([RolesSeeder::class, AreasSeeder::class, CategoriasSeeder::class]);
        }
    }

    protected function iniciarSesion(string $rol = 'Administrador'): Usuario
    {
        $this->sembrarCatalogosFijos();
        $r = Rol::where('Nombre', $rol)->firstOrFail();
        $u = Usuario::create([
            'Nombre_Usuario' => 'test_' . strtolower($rol) . '_' . uniqid(),
            'Password_Hash' => bcrypt('Os12345678'),
            'FkId_Rol' => $r->Id_Rol,
            'Estatus' => 'Activo',
        ]);
        $this->actingAs($u);

        return $u;
    }

    /** Crea puesto, ubicaciones, empleados, marca, tipo y modelo de prueba. Devuelve sus ids. */
    protected function datosBase(): array
    {
        $this->sembrarCatalogosFijos();
        $d = [];
        $d['puesto'] = DB::table('puestos')->insertGetId(['Nombre' => 'Analista']);
        $d['u1'] = DB::table('ubicaciones')->insertGetId(['Nombre' => 'Laboratorio de Computo', 'Edificio' => 'E', 'Planta' => '2']);
        $d['u2'] = DB::table('ubicaciones')->insertGetId(['Nombre' => 'Direccion', 'Edificio' => 'A', 'Planta' => '1']);
        $d['juan'] = DB::table('empleados')->insertGetId(['N_Trabajador' => 'E1', 'Nombre' => 'Juan', 'Apellido_Paterno' => 'Perez', 'FkId_Puesto' => $d['puesto'], 'FkId_Area' => 6]);
        $d['ana'] = DB::table('empleados')->insertGetId(['N_Trabajador' => 'E2', 'Nombre' => 'Ana', 'Apellido_Paterno' => 'Garcia', 'FkId_Puesto' => $d['puesto'], 'FkId_Area' => 1]);
        $d['luis'] = DB::table('empleados')->insertGetId(['N_Trabajador' => 'E3', 'Nombre' => 'Luis', 'Apellido_Paterno' => 'Ramos', 'FkId_Puesto' => $d['puesto'], 'FkId_Area' => 2]);
        $d['marca'] = DB::table('marcas')->insertGetId(['Nombre' => 'Dell']);
        $d['tipo'] = DB::table('tipos')->insertGetId(['Nombre' => 'Laptop', 'FkId_Categoria' => 2]);
        $d['modelo'] = DB::table('modelos')->insertGetId(['Nombre' => 'Latitude', 'FkId_Marca' => $d['marca'], 'FkId_Tipo' => $d['tipo']]);

        return $d;
    }

    protected function crearArticulo(array $d, ?string $codigo, int $ubicacion, int $empleado, ?string $serie = null, array $extra = []): int
    {
        return DB::table('articulos')->insertGetId(array_merge([
            'Descripcion' => 'Articulo ' . ($codigo ?? 'sin codigo'),
            'Codigo_Barras' => $codigo,
            'N_Serie' => $serie,
            'FkId_Marca' => $d['marca'],
            'FkId_Modelo' => $d['modelo'],
            'FkId_Categoria' => 2,
            'FkId_Tipo' => $d['tipo'],
            'FkId_Ubicacion' => $ubicacion,
            'FkId_Empleado' => $empleado,
            'Tipo_Articulo' => 'Capitalizable',
        ], $extra));
    }
}