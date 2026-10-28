<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\IniciaSesion;
use Tests\TestCase;

/** Matriz de permisos por rol (RF-26 a RF-29). */
class RolesAccesoTest extends TestCase
{
    use RefreshDatabase;
    use IniciaSesion;

    public function test_sin_sesion_redirige_a_login(): void
    {
        $this->get('/articulos')->assertRedirect('/login');
        $this->get('/revision/periodos')->assertRedirect('/login');
    }

    public function test_revisor_solo_accede_a_revision(): void
    {
        $this->iniciarSesion('Revisor');

        foreach (['/articulos', '/empleados', '/facturas', '/marcas', '/usuarios', '/vehiculos', '/revision/periodos/nuevo', '/articulos/generar-codigo'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->get('/revision/periodos')->assertOk();
        $this->get('/revision/historico')->assertOk();
    }

    public function test_consulta_es_solo_lectura(): void
    {
        $this->iniciarSesion('Consulta');

        $this->get('/articulos')->assertOk();
        $this->get('/empleados')->assertOk();
        $this->get('/revision/historico')->assertOk();
        foreach (['/articulos/create', '/empleados/create', '/marcas/create', '/vehiculos', '/usuarios', '/revision/periodos', '/revision/periodos/nuevo'] as $url) {
            $this->get($url)->assertForbidden();
        }
    }

    public function test_consulta_no_ve_vehiculos_ni_usuarios_en_el_menu(): void
    {
        $this->iniciarSesion('Consulta');

        $this->get('/articulos')->assertOk()
            ->assertDontSee('Vehículos escolares')
            ->assertDontSee('Cuentas de acceso');
    }

    public function test_administrador_accede_a_todo(): void
    {
        $this->iniciarSesion('Administrador');

        foreach (['/articulos', '/articulos/create', '/empleados', '/usuarios', '/vehiculos', '/revision/periodos', '/revision/periodos/nuevo', '/revision/historico'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
