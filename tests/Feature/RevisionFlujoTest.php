<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\IniciaSesion;
use Tests\TestCase;

/** Flujo de revisión (RF-15 a RF-25): periodo, resguardo por empleado, escaneo, reasignación y cierre. */
class RevisionFlujoTest extends TestCase
{
    use RefreshDatabase;
    use IniciaSesion;

    private array $d;
    private int $periodo;

    private function preparar(): void
    {
        $this->iniciarSesion('Administrador');
        $this->d = $this->datosBase();
        $this->periodo = DB::table('periodos')->insertGetId(['Nombre' => 'Auditoria QA', 'Fecha_Inicio' => '2026-10-21']);
    }

    private function iniciarResguardo(int $ubicacion, int $empleado): int
    {
        $this->post("/revision/periodos/{$this->periodo}/iniciar", ['FkId_Ubicacion' => $ubicacion, 'FkId_Empleado' => $empleado]);

        return (int) DB::table('resguardos')->where('FkId_Ubicacion', $ubicacion)->where('FkId_Empleado', $empleado)->value('Id_Resguardo');
    }

    public function test_la_lista_esperada_solo_trae_articulos_activos_del_empleado_en_esa_ubicacion(): void
    {
        $this->preparar();
        $d = $this->d;
        $a1 = $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);
        $a2 = $this->crearArticulo($d, 'INV-002', $d['u1'], $d['juan']);
        $this->crearArticulo($d, 'INV-004', $d['u1'], $d['ana']);                                      // otro empleado
        $this->crearArticulo($d, 'INV-005', $d['u2'], $d['juan']);                                     // otra ubicación
        $this->crearArticulo($d, 'INV-007', $d['u1'], $d['juan'], null, ['status' => 'inactivo']);   // dado de baja

        $rid = $this->iniciarResguardo($d['u1'], $d['juan']);

        $this->assertEqualsCanonicalizing([$a1, $a2], DB::table('resguardo_articulos')->where('FkId_Resguardo', $rid)->pluck('FkId_Articulo')->all());
    }

    public function test_no_crea_resguardo_si_el_empleado_no_tiene_articulos_en_la_ubicacion(): void
    {
        $this->preparar();
        $this->crearArticulo($this->d, 'INV-001', $this->d['u1'], $this->d['juan']);

        $this->post("/revision/periodos/{$this->periodo}/iniciar", ['FkId_Ubicacion' => $this->d['u1'], 'FkId_Empleado' => $this->d['luis']]);

        $this->assertSame(0, DB::table('resguardos')->count());
    }

    public function test_reiniciar_la_misma_combinacion_no_duplica_el_resguardo(): void
    {
        $this->preparar();
        $this->crearArticulo($this->d, 'INV-001', $this->d['u1'], $this->d['juan']);

        $this->iniciarResguardo($this->d['u1'], $this->d['juan']);
        $this->iniciarResguardo($this->d['u1'], $this->d['juan']);

        $this->assertSame(1, DB::table('resguardos')->count());
    }

    public function test_escaneo_por_codigo_serie_repetido_inexistente_y_otro_empleado(): void
    {
        $this->preparar();
        $d = $this->d;
        $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);
        $this->crearArticulo($d, 'INV-002', $d['u1'], $d['juan'], 'SERIE-M2');
        $this->crearArticulo($d, 'INV-004', $d['u1'], $d['ana']);
        $this->crearArticulo($d, 'INV-007', $d['u1'], $d['juan'], null, ['status' => 'inactivo']);
        $rid = $this->iniciarResguardo($d['u1'], $d['juan']);
        $url = "/revision/resguardos/$rid/buscar";

        $this->postJson($url, ['codigo' => 'INV-001'])->assertOk()->assertJson(['ok' => true, 'type' => 'reviewed']);
        $this->postJson($url, ['codigo' => 'SERIE-M2'])->assertOk()->assertJson(['ok' => true, 'type' => 'reviewed']);
        $this->postJson($url, ['codigo' => 'INV-001'])->assertOk()->assertJson(['type' => 'already_reviewed']);
        $this->postJson($url, ['codigo' => 'XXX-000'])->assertNotFound()->assertJson(['type' => 'not_found']);
        $this->postJson($url, ['codigo' => 'INV-007'])->assertNotFound();                       // dado de baja
        $this->postJson($url, ['codigo' => 'INV-004'])->assertOk()->assertJson(['ok' => false, 'type' => 'not_expected']);
    }

    public function test_articulo_de_otra_ubicacion_se_reasigna_y_queda_revisado(): void
    {
        $this->preparar();
        $d = $this->d;
        $this->crearArticulo($d, 'INV-001', $d['u1'], $d['juan']);
        $fuera = $this->crearArticulo($d, 'INV-005', $d['u2'], $d['ana']);
        $rid = $this->iniciarResguardo($d['u1'], $d['juan']);

        $this->postJson("/revision/resguardos/$rid/buscar", ['codigo' => 'INV-005'])->assertJson(['type' => 'not_expected']);
        $this->postJson("/revision/resguardos/$rid/reasignar/$fuera")->assertStatus(422);       // exige confirmación
        $this->postJson("/revision/resguardos/$rid/reasignar/$fuera", ['confirmacion' => 1])->assertOk()->assertJson(['ok' => true, 'created' => true]);

        $this->assertSame($d['u1'], (int) DB::table('articulos')->where('Id_Articulo', $fuera)->value('FkId_Ubicacion'));
        $this->assertSame(2, DB::table('resguardo_articulos')->where('FkId_Resguardo', $rid)->count());
        $this->assertSame(1, DB::table('resguardo_articulos')->where('FkId_Resguardo', $rid)->where('Revisado', 1)->count());
    }

    public function test_cerrar_resguardo_bloquea_escaneo_y_doble_cierre(): void
    {
        $this->preparar();
        $this->crearArticulo($this->d, 'INV-001', $this->d['u1'], $this->d['juan']);
        $rid = $this->iniciarResguardo($this->d['u1'], $this->d['juan']);

        $this->post("/revision/resguardos/$rid/cerrar", ['Observaciones' => str_repeat('a', 501)])->assertSessionHasErrors('Observaciones');
        $this->post("/revision/resguardos/$rid/cerrar", ['Observaciones' => 'Monitor no localizado'])->assertRedirect("/resguardos/$rid/imprimir");
        $this->post("/revision/resguardos/$rid/cerrar")->assertForbidden();
        $this->postJson("/revision/resguardos/$rid/buscar", ['codigo' => 'INV-001'])->assertForbidden();
        $this->get("/resguardos/$rid/imprimir")->assertOk()->assertSee('Empleado responsable');
    }

    public function test_no_se_cierra_el_periodo_con_resguardos_abiertos_y_solo_el_admin_cierra_y_reabre(): void
    {
        $this->preparar();
        $this->crearArticulo($this->d, 'INV-001', $this->d['u1'], $this->d['juan']);
        $rid = $this->iniciarResguardo($this->d['u1'], $this->d['juan']);

        $this->post("/revision/periodos/{$this->periodo}/cerrar");
        $this->assertSame('Abierto', DB::table('periodos')->where('Id_Periodo', $this->periodo)->value('Estado'));

        $this->post("/revision/resguardos/$rid/cerrar");
        $this->post("/revision/periodos/{$this->periodo}/cerrar");
        $this->assertSame('Cerrado', DB::table('periodos')->where('Id_Periodo', $this->periodo)->value('Estado'));

        $this->post("/revision/periodos/{$this->periodo}/iniciar", ['FkId_Ubicacion' => $this->d['u2'], 'FkId_Empleado' => $this->d['ana']])->assertForbidden();

        $this->iniciarSesion('Revisor');
        $this->post("/revision/periodos/{$this->periodo}/reabrir")->assertForbidden();
        $this->post("/revision/resguardos/$rid/reabrir")->assertForbidden();

        $this->iniciarSesion('Administrador');
        $this->post("/revision/periodos/{$this->periodo}/reabrir");
        $this->assertSame('Abierto', DB::table('periodos')->where('Id_Periodo', $this->periodo)->value('Estado'));
    }

    public function test_la_pantalla_de_escaneo_trae_layout_completo_script_cerrado_y_captura_manual(): void
    {
        $this->preparar();
        $this->crearArticulo($this->d, 'INV-001', $this->d['u1'], $this->d['juan']);
        $rid = $this->iniciarResguardo($this->d['u1'], $this->d['juan']);

        $html = $this->get("/revision/resguardos/$rid/escanear")->assertOk()->getContent();

        $this->assertStringStartsWith('<!DOCTYPE html>', ltrim($html), 'El layout debe abrir el documento (si no, la página sale sin estilos).');
        $this->assertSame(substr_count($html, '<script'), substr_count($html, '</script>'), 'Hay un <script> sin cerrar.');
        $this->assertStringContainsString('id="manualForm"', $html);
        $this->assertStringContainsString('id="reader"', $html);
        $this->assertStringContainsString('css/revision.css', $html);
        $this->assertStringContainsString('Responsable:', $html);
    }
}
