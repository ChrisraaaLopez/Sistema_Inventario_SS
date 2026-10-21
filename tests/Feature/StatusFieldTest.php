<?php

namespace Tests\Feature;

use App\Models\Marca;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusFieldTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_field_hides_inactive_catalog_records(): void
    {
        Marca::create(['Nombre' => 'Marca Activa', 'status' => 'activo']);
        Marca::create(['Nombre' => 'Marca Inactiva', 'status' => 'inactivo']);

        $response = $this->get(route('marcas.index'));

        $response->assertOk();
        $response->assertSee('Marca Activa');
        $response->assertDontSee('Marca Inactiva');
    }
}
