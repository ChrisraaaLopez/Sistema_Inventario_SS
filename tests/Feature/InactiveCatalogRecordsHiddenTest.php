<?php

namespace Tests\Feature;

use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\IniciaSesion;
use Tests\TestCase;

class InactiveCatalogRecordsHiddenTest extends TestCase
{
    use RefreshDatabase;
    use IniciaSesion;

    public function test_inactive_marcas_are_not_listed(): void
    {
        $this->iniciarSesion();
        Marca::create(['Nombre' => 'Activa', 'Estatus' => 'Activo']);
        Marca::create(['Nombre' => 'Inactiva', 'Estatus' => 'Inactivo']);

        $response = $this->get(route('marcas.index'));

        $response->assertOk();
        $response->assertSee('Activa');
        $response->assertDontSee('Inactiva');
    }
}
