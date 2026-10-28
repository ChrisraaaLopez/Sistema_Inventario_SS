<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticuloCodigoGeneradoTest extends TestCase
{
    use RefreshDatabase;

    private function createRoleAndUser(string $rolNombre, string $usuarioNombre): \App\Models\Usuario
    {
        $rolId = \DB::table('roles')->insertGetId([
            'Nombre' => $rolNombre,
            'Estatus' => 'Activo',
            'status' => 'activo',
        ]);

        $usuario = \App\Models\Usuario::create([
            'Nombre_Usuario' => $usuarioNombre,
            'Password_Hash' => \Hash::make('Os1234'),
            'FkId_Rol' => $rolId,
            'Estatus' => 'Activo',
            'status' => 'activo',
        ]);

        return $usuario;
    }

    private function createArticulo(array $overrides = []): int
    {
        $areaId = \DB::table('areas')->insertGetId([
            'Nombre' => 'Tecnología',
            'Estatus' => 'Activo',
        ]);

        $puestoId = \DB::table('puestos')->insertGetId([
            'Nombre' => 'Analista',
            'Estatus' => 'Activo',
        ]);

        $marcaId = \DB::table('marcas')->insertGetId([
            'Nombre' => 'Dell',
            'Estatus' => 'Activo',
        ]);

        $categoriaId = \DB::table('categorias')->insertGetId([
            'Nombre' => 'Tecnología',
            'Estatus' => 'Activo',
        ]);

        $tipoId = \DB::table('tipos')->insertGetId([
            'Nombre' => 'Laptop',
            'FkId_Categoria' => $categoriaId,
            'Estatus' => 'Activo',
        ]);

        $ubicacionId = \DB::table('ubicaciones')->insertGetId([
            'Nombre' => 'Laboratorio',
            'Edificio' => 'A',
            'Planta' => '1',
            'Estatus' => 'Activo',
        ]);

        $empleadoId = \DB::table('empleados')->insertGetId([
            'N_Trabajador' => 'TSJ-' . uniqid('', true),
            'Nombre' => 'Empleado',
            'Apellido_Paterno' => 'Prueba',
            'Apellido_Materno' => 'Ninguno',
            'FkId_Puesto' => $puestoId,
            'FkId_Area' => $areaId,
            'Estatus' => 'Activo',
        ]);

        $modeloId = \DB::table('modelos')->insertGetId([
            'Nombre' => 'Latitude 5440',
            'FkId_Marca' => $marcaId,
            'FkId_Tipo' => $tipoId,
            'Estatus' => 'Activo',
        ]);

        $defaults = [
            'Descripcion' => 'Laptop de prueba',
            'FkId_Marca' => $marcaId,
            'FkId_Modelo' => $modeloId,
            'N_Serie' => 'SER-001',
            'Color' => 'Negro',
            'FkId_Categoria' => $categoriaId,
            'FkId_Tipo' => $tipoId,
            'FkId_Ubicacion' => $ubicacionId,
            'FkId_Empleado' => $empleadoId,
            'FkId_Factura' => null,
            'Notas' => 'Nota',
            'Comentarios' => 'Comentario',
            'Estado' => 'Bien',
            'Tipo_Articulo' => 'Capitalizable',
            'Estatus' => 'Activo',
            'status' => 'activo',
        ];

        return \DB::table('articulos')->insertGetId(array_merge($defaults, $overrides));
    }

    public function test_generar_codigo_ignora_los_ya_existentes_incluyendo_inactivos_y_n_serie(): void
    {
        $admin = $this->createRoleAndUser('Administrador', 'admin-codigo');
        $this->actingAs($admin, 'web');

        $this->createArticulo(['Codigo_Barras' => 'INV-000008', 'N_Serie' => 'SER-100']);
        $this->createArticulo([
            'Descripcion' => 'Equipo inactivo',
            'Codigo_Barras' => 'INV-000010',
            'N_Serie' => 'SER-101',
            'Estatus' => 'Inactivo',
            'status' => 'inactivo',
        ]);
        $this->createArticulo([
            'Descripcion' => 'Equipo con serie conflictiva',
            'Codigo_Barras' => null,
            'N_Serie' => 'INV-000011',
            'Estatus' => 'Activo',
            'status' => 'activo',
        ]);

        $response = $this->getJson(route('articulos.generar-codigo'));

        $response->assertOk();
        $response->assertJsonPath('codigo', 'INV-000012');
    }

    public function test_revisor_y_consulta_reciben_403_al_generar_codigo(): void
    {
        $revisor = $this->createRoleAndUser('Revisor', 'revisor-codigo');
        $consulta = $this->createRoleAndUser('Consulta', 'consulta-codigo');

        $this->actingAs($revisor, 'web');
        $this->getJson(route('articulos.generar-codigo'))->assertStatus(403);

        $this->actingAs($consulta, 'web');
        $this->getJson(route('articulos.generar-codigo'))->assertStatus(403);
    }

    public function test_detalle_con_codigo_muestra_reimprimir_y_sin_codigo_no_lo_muestra(): void
    {
        $admin = $this->createRoleAndUser('Administrador', 'admin-detalle');
        $this->actingAs($admin, 'web');

        $conCodigo = $this->createArticulo([
            'Descripcion' => 'Artículo con código',
            'Codigo_Barras' => 'INV-000020',
            'N_Serie' => 'SER-020',
        ]);

        $sinCodigo = $this->createArticulo([
            'Descripcion' => 'Artículo sin código',
            'Codigo_Barras' => null,
            'N_Serie' => 'SER-021',
        ]);

        $this->get(route('articulos.show', $conCodigo))
            ->assertOk()
            ->assertSee('Reimprimir');

        $this->get(route('articulos.show', $sinCodigo))
            ->assertOk()
            ->assertDontSee('Reimprimir');
    }
}
