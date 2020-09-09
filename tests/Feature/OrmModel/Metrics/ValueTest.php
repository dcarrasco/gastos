<?php

namespace Tests\Unit\OrmModel\Metrics;

use Tests\TestCase;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use App\Models\Gastos\Banco;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\Cuenta;
use App\OrmModel\src\Resource;
use App\Models\Gastos\TipoGasto;
use App\Models\Gastos\TipoCuenta;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Metrics\Value;
use App\Models\Gastos\TipoMovimiento;
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

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('dateFormat', $this->value);
        $this->assertObjectHasAttribute('prefix', $this->value);
        $this->assertObjectHasAttribute('suffix', $this->value);
    }

    public function testCalculate()
    {
        $request = $this->makeMock(Request::class, []);

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

        $request = $this->makeMock(Request::class, ['get', 'input', 'has']);
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
        $request = $this->makeMock(Request::class, ['get', 'input', 'has']);

        $this->value = new class() extends Value {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 50,
                ];
            }

        };

        $this->assertEquals([
            'currentValue' => ' 100 ',
            'previousValue' => '100% de aumento',
            'trendIconStyle' => 'up',
            'script' => new HtmlString(''),
        ], $this->value->content($request)->toHtml()->getData());

        $value2 = new class() extends Value {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 50,
                    'previousValue' => 100,
                ];
            }
        };

        $this->assertEquals([
            'currentValue' => ' 50 ',
            'previousValue' => '-50% de disminucion',
            'trendIconStyle' => 'down',
            'script' => new HtmlString(''),
        ], $value2->content($request)->toHtml()->getData());

        $value3 = new class() extends Value {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 100,
                ];
            }
        };

        $this->assertEquals([
            'currentValue' => 'p 100 s',
            'previousValue' => '0% de aumento',
            'trendIconStyle' => 'up',
            'script' => new HtmlString(''),
        ], $value3->prefix('p')->suffix('s')->content($request)->toHtml()->getData());

        $value4 = new class() extends Value {
            public function calculate(Request $request): array
            {
                return [
                    'currentValue' => 100,
                    'previousValue' => 0,
                ];
            }
        };

        $this->assertEquals([
            'currentValue' => ' 100 ',
            'previousValue' => 'Sin datos anteriores',
            'trendIconStyle' => 'none',
            'script' => new HtmlString(''),
        ], $value4->content($request)->toHtml()->getData());
    }
}
