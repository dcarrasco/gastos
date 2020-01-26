<?php

namespace Tests\Unit;

// use Tests\TestCase;
use App\Gastos\Cuenta;
use App\Gastos\TipoCuenta;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BancoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $tipoCuentaGasto = factory(TipoCuenta::class)->create();
        $tipoCuentaInversion = factory(TipoCuenta::class)->create([
            'tipo' => TipoCuenta::CUENTA_INVERSION,
        ]);

        $cuentasGastos = factory(Cuenta::class, 3)->create([
            'banco_id' => 1,
            'tipo_cuenta_id' => 1,
        ]);

        $cuentasInversion = factory(Cuenta::class, 3)->create([
            'banco_id' => 1,
            'tipo_cuenta_id' => 2,
        ]);

        $this->assertEquals($cuentasGastos->pluck('cuenta', 'id')->all(), Cuenta::selectCuentasGastos());
        $this->assertEquals($cuentasInversion->pluck('cuenta', 'id')->all(), Cuenta::selectCuentasInversiones());
    }
}
