<?php

namespace Tests\Feature\OrmModel\Metrics;

use App\Models\Acl\Usuario;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Cuenta;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\TipoCuenta;
use App\Models\Gastos\TipoGasto;
use App\Models\Gastos\TipoMovimiento;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ValueTest extends TestCase
{
    use RefreshDatabase;

    protected $value;

    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->value = new class() extends Value
        {
        };
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('dateFormat', $this->value);
        $this->assertObjectHasAttribute('prefix', $this->value);
        $this->assertObjectHasAttribute('suffix', $this->value);
    }

    public function testCalculate()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsArray($this->value->calculate($request));
    }

    public function testAggregators()
    {
        $banco = Banco::factory()->create();
        $tipoCuenta = TipoCuenta::factory()->create();

        $cuenta = Cuenta::factory()->create([
            'banco_id' => $banco->id,
            'tipo_cuenta_id' => $tipoCuenta->id,
        ]);

        $tipoMovimiento = TipoMovimiento::factory()->create();
        $tipoGasto = TipoGasto::factory()->create([
            'tipo_movimiento_id' => $tipoMovimiento->id,
        ]);

        $usuario = Usuario::factory()->create();

        $montos = [100, 200, 300, 400, 500];

        collect($montos)->each(function ($monto) use ($cuenta, $tipoGasto, $tipoMovimiento, $usuario) {
            $gasto = Gasto::factory()->create([
                'monto' => $monto,
                'cuenta_id' => $cuenta->id,
                'tipo_gasto_id' => $tipoGasto->id,
                'tipo_movimiento_id' => $tipoMovimiento->id,
                'usuario_id' => $usuario->id,
            ]);
        });

        $request = $this->createMock(Request::class);
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

    public function testPrefix()
    {
        $this->assertEquals(Value::class, get_parent_class($this->value->prefix('prefix')));
    }

    public function testSuffix()
    {
        $this->assertEquals(Value::class, get_parent_class($this->value->suffix('prefix')));
    }

    public function testContent()
    {
        $request = $this->createMock(Request::class);

        $this->value = new class() extends Value
        {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 50,
                ];
            }
        };

        $this->assertStringContainsString('100', $this->value->content($request)->toHtml());
        $this->assertStringContainsString('100% de aumento', $this->value->content($request)->toHtml());
        $this->assertStringContainsString('fill: #38c172', $this->value->content($request)->toHtml());

        $value2 = new class() extends Value
        {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 50,
                    'previousValue' => 100,
                ];
            }
        };

        $this->assertStringContainsString('50', $value2->content($request)->toHtml());
        $this->assertStringContainsString('-50% de disminucion', $value2->content($request)->toHtml());
        $this->assertStringContainsString('fill: #e3342f', $value2->content($request)->toHtml());

        $value3 = new class() extends Value
        {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 100,
                ];
            }
        };
        $value3 = $value3->prefix('p')->suffix('s');

        $this->assertStringContainsString('p 100 s', $value3->content($request)->toHtml());
        $this->assertStringContainsString('0% de aumento', $value3->content($request)->toHtml());
        $this->assertStringContainsString('fill: #38c172', $value3->content($request)->toHtml());

        $value4 = new class() extends Value
        {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 0,
                ];
            }
        };
        $this->assertStringContainsString('100', $value4->content($request)->toHtml());
        $this->assertStringContainsString('Sin datos anteriores', $value4->content($request)->toHtml());
        $this->assertStringContainsString('display: none', $value4->content($request)->toHtml());
    }

    public function testContentAjaxRequest()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsArray($this->value->contentAjaxRequest($request));
    }
}
