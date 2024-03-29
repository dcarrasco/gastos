<?php

namespace Tests\Feature\OrmModel\OrmField;

use App\OrmModel\src\OrmField\Select;
use App\OrmModel\src\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class SelectTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class('nombreCampo') extends Select
        {
        };
    }

    public function testOptions()
    {
        $this->assertIsObject($this->field->options(['a' => 'b', 'c' => 'd']));
    }

    public function testHasChoices()
    {
        $this->assertFalse($this->field->hasChoices());
        $this->assertTrue($this->field->options(['a' => 'b', 'c' => 'd'])->hasChoices());
    }

    public function testGetFormattedValue()
    {
        $opciones = ['opc1' => 'valor1', 'opc2' => 'valor2'];

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('opc1');

        $request = $this->createMock(Request::class);

        // sin opciones
        $this->assertEquals(new HtmlString('opc1'), $this->field->resolveValue($model, $request)->getFormattedValue());

        $this->assertEquals(
            new HtmlString('valor1'),
            $this->field->options($opciones)->resolveValue($model, $request)->getFormattedValue()
        );

        $model2 = $this->createMock(Model::class);
        $model2->expects($this->any())->method('getAttribute')->willReturn('opc_nueva');
        $this->assertEquals(
            new HtmlString(''),
            $this->field->options($opciones)->resolveValue($model2, $request)->getFormattedValue()
        );
    }

    public function testGetForm()
    {
        $opciones = ['opc1' => 'valor1', 'opc2' => 'valor2'];

        $session = $this->createMock(Request::class);
        $session->expects($this->any())->method('get')->willReturn([]);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('session')->willReturn($session);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('opc1');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertStringContainsString('select', $this->field->options($opciones)->getForm($request, $resource));
        $this->assertStringContainsString(
            'name="nombre_campo"',
            $this->field->options($opciones)->getForm($request, $resource)
        );
        $this->assertStringContainsString(
            '<option value="opc1" selected>',
            $this->field->options($opciones)->getForm($request, $resource)
        );
    }
}
