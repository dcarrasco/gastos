<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Acl\Usuario;
use App\Gastos\Banco;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use App\Gastos\TipoCuenta;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use App\OrmModel\src\Resource;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValueTest extends TestCase
{
    use RefreshDatabase;

    protected $value;
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->value = new class() extends Value {
        };
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('dateFormat', $this->value);
        $this->assertObjectHasAttribute('prefix', $this->value);
        $this->assertObjectHasAttribute('suffix', $this->value);
    }

    public function testAggregators()
    {
        $banco = factory(Banco::class)->create();
        $tipoCuenta = factory(TipoCuenta::class)->create();

        $cuenta = factory(Cuenta::class)->create([
            'banco_id' => $banco->id,
            'tipo_cuenta_id' => $tipoCuenta->id,
        ]);

        $tipoMovimiento = factory(TipoMovimiento::class)->create();
        $tipoGasto = factory(TipoGasto::class)->create([
            'tipo_movimiento_id' => $tipoMovimiento->id,
        ]);

        $usuario = factory(Usuario::class)->create();

        $montos = [100, 200, 300, 400, 500];

        collect($montos)->each(function ($monto) use ($cuenta, $tipoGasto, $tipoMovimiento, $usuario) {
            $gasto = factory(Gasto::class)->create([
                'monto' => $monto,
                'cuenta_id' => $cuenta->id,
                'tipo_gasto_id' => $tipoGasto->id,
                'tipo_movimiento_id' => $tipoMovimiento->id,
                'usuario_id' => $usuario->id,
            ]);
        });

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'input', 'has'])
            ->getMock();
        $request->expects($this->any())->method('get')->willReturn('valor');
        $request->expects($this->any())->method('input')->willReturn('MTD');

        $this->assertEquals(
            ['currentValue' => 1500, 'previousValue' => 0],
            $this->value->sum($request, \App\OrmModel\Gastos\Gasto::class, 'monto', 'fecha')
        );

        $this->assertEquals(
            ['currentValue' => 100, 'previousValue' => 0],
            $this->value->min($request, \App\OrmModel\Gastos\Gasto::class, 'monto', 'fecha')
        );

        $this->assertEquals(
            ['currentValue' => 500, 'previousValue' => 0],
            $this->value->max($request, \App\OrmModel\Gastos\Gasto::class, 'monto', 'fecha')
        );

        $this->assertEquals(
            ['currentValue' => 300, 'previousValue' => 0],
            $this->value->average($request, \App\OrmModel\Gastos\Gasto::class, 'monto', 'fecha')
        );

        $this->assertEquals(
            ['currentValue' => 5, 'previousValue' => 0],
            $this->value->count($request, \App\OrmModel\Gastos\Gasto::class, 'fecha')
        );
    }
}
