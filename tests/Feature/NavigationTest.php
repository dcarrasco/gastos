<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\Usuario;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\TipoGasto;
use App\Models\Gastos\TipoCuenta;
use App\Models\Gastos\TipoMovimiento;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    protected $usuario;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->usuario = Usuario::find(1);

        $banco = Banco::factory()->create();
        $tipoCuenta = TipoCuenta::factory()->create();
        $tipoCuentaInversion = TipoCuenta::factory()->create(['tipo' => TipoCuenta::CUENTA_INVERSION]);
        $cuenta = Cuenta::factory()->create(['banco_id' => $banco->id, 'tipo_cuenta_id' => $tipoCuenta->id]);
        $cuentaInversion = Cuenta::factory()
            ->create(['banco_id' => $banco->id, 'tipo_cuenta_id' => $tipoCuentaInversion->id]);
        $tiposMovimientos = TipoMovimiento::factory(5)->create();
        $tipoGasto = TipoGasto::factory()->create(['tipo_movimiento_id' => '1']);
        $gasto = Gasto::factory()->create([
            'cuenta_id' => $cuenta->id,
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => $tiposMovimientos[0]->id,
            'usuario_id' => $this->usuario->id,
        ]);
    }

    public function urlGetDataProvider()
    {
        return [
            'home' => ['get', '/home'],
            'gastos' => ['get', '/gastos/ingresar'],
            'reporte' => ['get', '/gastos/reporte'],
            'detalle reporte' => ['get', '/gastos/reporte/detalle?cuenta_id=1&anno=2020&mes=1&tipo_gasto_id=1'],
            'reporte total' => ['get', '/gastos/reporte-total-gastos'],
            'inversion' => ['get', '/gastos/inversion'],
            'ingreso masivo' => ['get', '/gastos/ingreso-masivo'],
            'config gastos' => ['get', '/gastos-config'],
            'config acl' => ['get', '/acl-config'],
        ];
    }

    public function urlPostDataProvider()
    {
        return [
            'ingreso gasto' => ['post', '/gastos/ingresar', [
                'cuenta_id' => '1', 'anno' => 2020, 'mes' => 1, 'fecha' => '2020-01-01', 'glosa' => 'aa',
                'serie' => '123', 'tipo_gasto_id' => 1, 'monto' => 123
            ]],
            'ingreso inversion' => ['post', '/gastos/inversion', [
                'cuenta_id' => '1', 'anno' => 2020, 'mes' => 1, 'fecha' => '2020-01-01', 'glosa' => 'aa',
                'serie' => '123', 'tipo_gasto_id' => 1, 'monto' => 123
            ]],
            'borrar gasto' => ['delete', '/gastos/ingresar/1', []],
        ];
    }


    /**
     * @dataProvider urlGetDataProvider
     */
    public function testGetUrl($method, $url)
    {
        $this->actingAs($this->usuario)->{$method}($url)->assertStatus(200);
    }

    /**
     * @dataProvider urlPostDataProvider
     */
    public function testPostUrl($method, $url, $data)
    {
        $this->actingAs($this->usuario)->{$method}($url, $data)->assertStatus(302);
    }
}
