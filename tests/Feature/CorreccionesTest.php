<?php

namespace Tests\Feature;

use App\Models\Articulo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\IniciaSesion;
use Tests\TestCase;

/** Regresiones: errores que ya se corrigieron y no deben volver. */
class CorreccionesTest extends TestCase
{
    use RefreshDatabase;
    use IniciaSesion;

    private function payloadArticulo(array $d, array $extra = []): array
    {
        return array_merge([
            'Descripcion' => 'Laptop', 'FkId_Marca' => $d['marca'], 'FkId_Modelo' => $d['modelo'], 'FkId_Categoria' => 2,
            'FkId_Tipo' => $d['tipo'], 'FkId_Ubicacion' => $d['u1'], 'FkId_Empleado' => $d['juan'], 'Estado' => 'Bien', 'Tipo_Articulo' => 'Capitalizable',
        ], $extra);
    }

    public function test_editar_un_articulo_no_truena(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $id = $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);

        $this->put(route('articulos.update', $id), $this->payloadArticulo($d, ['Descripcion' => 'Laptop editada', 'Codigo_Barras' => 'INV-001', 'FkId_Ubicacion' => $d['u2']]))
            ->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame('Laptop editada', Articulo::find($id)->Descripcion);
        $this->assertSame($d['u2'], (int) Articulo::find($id)->FkId_Ubicacion);
    }

    public function test_rechaza_un_modelo_que_no_pertenece_a_la_marca(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $otraMarca = DB::table('marcas')->insertGetId(['Nombre' => 'HP', 'Estatus' => 'Activo']);

        $this->post(route('articulos.store'), $this->payloadArticulo($d, ['FkId_Marca' => $otraMarca]))->assertSessionHasErrors('FkId_Modelo');
    }

    public function test_empleado_con_estatus_baja_se_guarda_como_inactivo(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();

        $this->post(route('empleados.store'), [
            'N_Trabajador' => 'E99', 'Nombre' => 'Rosa', 'Apellido_Paterno' => 'Lopez', 'FkId_Puesto' => $d['puesto'], 'FkId_Area' => 1, 'Estatus' => 'Baja',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $e = DB::table('empleados')->where('N_Trabajador', 'E99')->first();
        $this->assertSame('inactivo', $e->status);
        $this->assertSame('Inactivo', $e->Estatus);
    }

    public function test_baja_de_articulo_exige_tipo_y_guarda_usuario_sin_duplicar(): void
    {
        $u = $this->iniciarSesion();
        $d = $this->datosBase();
        $id = $this->crearArticulo($d, 'INV-007', $d['u1'], $d['juan']);

        $this->delete(route('articulos.destroy', $id), ['Motivo' => 'x'])->assertSessionHasErrors('Tipo_Articulo');
        $this->delete(route('articulos.destroy', $id), ['Tipo_Articulo' => 'Baja', 'Motivo' => 'Obsoleto'])->assertRedirect();

        $this->assertSame(1, DB::table('articulos_bajas')->where('FkId_Articulo', $id)->count());
        $this->assertSame($u->Id_Usuario, (int) DB::table('articulos_bajas')->value('FkId_Usuario'));
        $this->assertSame('Baja', DB::table('articulos')->where('Id_Articulo', $id)->value('Tipo_Articulo'));
    }

    public function test_catalogos_no_aceptan_duplicados(): void
    {
        $this->iniciarSesion();
        $this->post(route('marcas.store'), ['Nombre' => 'HP', 'Estatus' => 'Activo'])->assertSessionHasNoErrors();
        $this->post(route('marcas.store'), ['Nombre' => 'HP', 'Estatus' => 'Activo'])->assertSessionHasErrors();
        $this->post(route('puestos.store'), ['Nombre' => 'Coordinador', 'Estatus' => 'Activo'])->assertSessionHasNoErrors();
        $this->post(route('puestos.store'), ['Nombre' => 'Coordinador', 'Estatus' => 'Activo'])->assertSessionHasErrors();
    }

    public function test_detalle_muestra_reimpresion_solo_si_hay_codigo(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $con = $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);
        $sin = $this->crearArticulo($d, null, $d['u1'], $d['juan']);

        $this->get(route('articulos.show', $con))->assertOk()->assertSee('Reimprimir código de barras y QR')->assertSee('id="barcodeShow"', false);
        $this->get(route('articulos.show', $sin))->assertOk()->assertDontSee('Reimprimir código de barras y QR')->assertSee('no tiene código');
    }

    public function test_formulario_de_modelos_ofrece_archivo_y_tomar_foto(): void
    {
        $this->iniciarSesion();
        $this->get(route('modelos.create'))->assertOk()
            ->assertSee('name="imagen"', false)->assertSee('type="file"', false)->assertSee('Tomar foto');
    }

    public function test_ninguna_vista_depende_de_internet(): void
    {
        $this->iniciarSesion();
        $d = $this->datosBase();
        $id = $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);

        foreach (['/articulos', '/articulos/create', "/articulos/$id", '/revision/periodos', '/modelos/create'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertDoesNotMatchRegularExpression('#<(script|link)[^>]+(src|href)="https?://(?!localhost)[^"]*"#i', $html, "$url carga recursos externos");
        }
    }
}
