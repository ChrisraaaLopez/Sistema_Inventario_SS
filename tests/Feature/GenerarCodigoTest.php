<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\IniciaSesion;
use Tests\TestCase;

/** El generador nunca debe devolver un código que ya exista (Codigo_Barras o N_Serie, activo o inactivo). */
class GenerarCodigoTest extends TestCase
{
    use RefreshDatabase;
    use IniciaSesion;

    public function test_genera_el_primer_codigo_cuando_no_hay_ninguno(): void
    {
        $this->iniciarSesion();
        $this->getJson('/articulos/generar-codigo')->assertOk()->assertJson(['codigo' => 'INV-000001']);
    }

    public function test_salta_codigos_existentes_en_codigo_serie_e_inactivos(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $this->crearArticulo($d, 'INV-000001', $d['u1'], $d['juan']);                                  // ya existe como código
        $this->crearArticulo($d, 'OTRO-1', $d['u1'], $d['juan'], 'INV-000002');                       // existe como número de serie
        $this->crearArticulo($d, 'INV-000003', $d['u1'], $d['juan'], null, ['status' => 'inactivo']); // inactivo / dado de baja

        $codigo = $this->getJson('/articulos/generar-codigo')->assertOk()->json('codigo');

        $this->assertSame('INV-000004', $codigo);
    }

    public function test_ignora_los_codigos_viejos_de_10_digitos(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $this->crearArticulo($d, 'INV-2873475147', $d['u1'], $d['juan']);

        $this->getJson('/articulos/generar-codigo')->assertOk()->assertJson(['codigo' => 'INV-000001']);
    }

    public function test_solo_el_administrador_puede_generar(): void
    {
        $this->iniciarSesion('Consulta');
        $this->getJson('/articulos/generar-codigo')->assertForbidden();
    }

    public function test_no_permite_guardar_un_codigo_repetido(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $this->crearArticulo($d, 'INV-000001', $d['u1'], $d['juan']);

        $payload = [
            'Descripcion' => 'Duplicado', 'Codigo_Barras' => 'INV-000001', 'FkId_Marca' => $d['marca'], 'FkId_Modelo' => $d['modelo'],
            'FkId_Categoria' => 2, 'FkId_Tipo' => $d['tipo'], 'FkId_Ubicacion' => $d['u1'], 'FkId_Empleado' => $d['juan'],
            'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable',
        ];

        $this->post(route('articulos.store'), $payload)
            ->assertSessionHasErrors(['Codigo_Barras' => 'Ese código ya está registrado en otro artículo.']);
    }
}
