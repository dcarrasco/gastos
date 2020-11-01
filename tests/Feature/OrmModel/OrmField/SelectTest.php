<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelectTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);

        $this->field = new class('nombreCampo') extends Select {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testOptions()
    {
        $this->assertIsObject($this->field->options(['a'=>'b', 'c'=>'d']));
    }

    public function testHasChoices()
    {
        $this->assertFalse($this->field->hasChoices());
        $this->assertTrue($this->field->options(['a'=>'b', 'c'=>'d'])->hasChoices());
    }

    public function testGetFormattedValue()
    {
        $opciones = ['opc1'=>'valor1', 'opc2'=>'valor2'];

        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn('opc1');

        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);

        // sin opciones
        $this->assertEquals('opc1', $this->field->getFormattedValue($model, $request));

        $this->assertEquals('valor1', $this->field->options($opciones)->getFormattedValue($model, $request));

        $model2 = $this->makeMock(Model::class, ['__get']);
        $model2->expects($this->any())->method('__get')->willReturn('opc_nueva');
        $this->assertEquals('', $this->field->options($opciones)->getFormattedValue($model2, $request));
    }

    public function testGetForm()
    {
        $opciones = ['opc1'=>'valor1', 'opc2'=>'valor2'];

        $session = $this->makeMock(Request::class, ['get']);
        $session->expects($this->any())->method('get')->willReturn([]);

        $request = $this->makeMock(Request::class, ['input', 'all', 'session']);
        $request->expects($this->any())->method('session')->willReturn($session);

        $model = $this->makeMock(Model::class, []);
        $model->expects($this->any())->method('__get')->willReturn('opc1');

        $resource = $this->makeMock(Resource::class, ['model', 'get']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertStringContainsString('select', $this->field->options($opciones)->getForm($request, $resource));
        $this->assertStringContainsString('name="nombre_campo"', $this->field->options($opciones)->getForm($request, $resource));
        $this->assertStringContainsString('<option value="opc1" selected>valor1</option>', $this->field->options($opciones)->getForm($request, $resource));
    }
}
