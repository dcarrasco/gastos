<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\Usuario;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\TipoCuenta;
use App\Models\Gastos\TipoGasto;
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
        $cuentaInversion = Cuenta::factory()->create(['banco_id' => $banco->id, 'tipo_cuenta_id' => $tipoCuentaInversion->id]);
        $tiposMovimientos = TipoMovimiento::factory(5)->create();
        $tipoGasto = TipoGasto::factory()->create(['tipo_movimiento_id' => '1']);
    }

    public function urlGetDataProvider()
    {
        return [
            ['get', '/home'],
            ['get', '/gastos/ingresar'],
            ['get', '/gastos/reporte'],
            // ['get', '/gastos/reporte/detalle/1'],
            ['get', '/gastos/reporte-total-gastos'],
            ['get', '/gastos/inversion'],
            ['get', '/gastos/ingreso-masivo'],
        ];
    }

    public function urlPostDataProvider()
    {
        return [
            ['post', '/gastos/ingresar', ['cuenta_id'=>'1', 'anno'=>2020, 'mes'=>1, 'fecha'=>'2020-01-01', 'glosa'=>'aa', 'serie'=>'123', 'tipo_gasto_id'=>1, 'monto'=>123]],
            ['post', '/gastos/inversion', ['cuenta_id'=>'1', 'anno'=>2020, 'mes'=>1, 'fecha'=>'2020-01-01', 'glosa'=>'aa', 'serie'=>'123', 'tipo_gasto_id'=>1, 'monto'=>123]],
            // ['delete', '/gastos/ingresar', []],
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
