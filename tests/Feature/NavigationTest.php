<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\Usuario;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Cuenta;
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
        $cuentaInversion = Cuenta::factory()->create(['banco_id' => $banco->id, 'tipo_cuenta_id' => $tipoCuentaInversion->id]);
        $tiposMovimientos = TipoMovimiento::factory(5)->create();
    }

    protected function response(string $url)
    {
        return $this->actingAs($this->usuario)->get($url);
    }

    public function urlDataProvider()
    {
        return [
            ['/gastos/ingresar'],
            ['/gastos/reporte'],
            ['/gastos/reporte-total-gastos'],
            ['/gastos/inversion'],
            ['/gastos/ingreso-masivo'],
        ];
    }

    /**
     * @dataProvider urlDataProvider
     */
    public function testReporte($url)
    {
        $this->response($url)->assertStatus(200);
    }
}
