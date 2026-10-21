<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticuloImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_upload_an_image_for_an_article(): void
    {
        Storage::fake('public');

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
            'N_Trabajador' => 'TSJ-100',
            'Nombre' => 'Juan',
            'Apellido_Paterno' => 'Pérez',
            'Apellido_Materno' => 'López',
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

        $response = $this->post(route('articulos.store'), [
            'Descripcion' => 'Laptop para pruebas',
            'FkId_Marca' => $marcaId,
            'FkId_Modelo' => $modeloId,
            'N_Serie' => 'SER-001',
            'Color' => 'Negro',
            'FkId_Categoria' => $categoriaId,
            'FkId_Tipo' => $tipoId,
            'FkId_Ubicacion' => $ubicacionId,
            'FkId_Empleado' => $empleadoId,
            'FkId_Factura' => null,
            'Notas' => 'Prueba de imagen',
            'Comentarios' => 'Subida de archivo',
            'Estado' => 'Bien',
            'Tipo_Articulo' => 'Capitalizable',
            'imagen' => UploadedFile::fake()->image('equipo.jpg'),
        ]);

        $response->assertRedirect(route('articulos.index'));

        $this->assertDatabaseHas('articulos', [
            'Descripcion' => 'Laptop para pruebas',
        ]);

        $articulo = \App\Models\Articulo::first();
        $this->assertNotNull($articulo->URL_Imagen);
        $this->assertStringStartsWith('articulos/', $articulo->URL_Imagen);
        Storage::disk('public')->assertExists($articulo->URL_Imagen);
    }

    public function test_article_image_is_visible_in_the_articles_index(): void
    {
        Storage::fake('public');

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
            'N_Trabajador' => 'TSJ-101',
            'Nombre' => 'Ana',
            'Apellido_Paterno' => 'García',
            'Apellido_Materno' => 'Luna',
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

        $path = \Illuminate\Support\Facades\Storage::disk('public')->put('articulos/test.jpg', 'test');

        \App\Models\Articulo::create([
            'Descripcion' => 'Laptop con imagen',
            'FkId_Marca' => $marcaId,
            'FkId_Modelo' => $modeloId,
            'N_Serie' => 'SER-002',
            'Color' => 'Negro',
            'FkId_Categoria' => $categoriaId,
            'FkId_Tipo' => $tipoId,
            'FkId_Ubicacion' => $ubicacionId,
            'FkId_Empleado' => $empleadoId,
            'FkId_Factura' => null,
            'Notas' => 'Vista en listado',
            'Comentarios' => 'Debe verse en el index',
            'Estado' => 'Bien',
            'Tipo_Articulo' => 'Capitalizable',
            'URL_Imagen' => $path,
        ]);

        $response = $this->get(route('articulos.index'));

        $response->assertOk();
        $response->assertSee('/storage/' . $path);
    }
}
