<?php

namespace Tests\Feature;

use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InactiveCatalogRecordsHiddenTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_marcas_are_not_listed(): void
    {
        Marca::create(['Nombre' => 'Activa', 'Estatus' => 'Activo']);
        Marca::create(['Nombre' => 'Inactiva', 'Estatus' => 'Inactivo']);

        $response = $this->get(route('marcas.index'));

        $response->assertOk();
        $response->assertSee('Activa');
        $response->assertDontSee('Inactiva');
    }
}
