<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Optional;
use App\OrmModel\src\OrmField\Boolean;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooleanTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class('nombreCampo') extends Boolean {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testGetFormattedValue()
    {
        $request = $this->makeMock(Request::class, []);

        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn(1);

        $this->assertStringContainsString('span', $this->field->getFormattedValue($model, $request));
        $this->assertStringContainsString('green', $this->field->getFormattedValue($model, $request));

        $model2 = $this->makeMock(Model::class, ['__get']);
        $model2->expects($this->any())->method('__get')->willReturn(0);
        $this->assertStringContainsString('red', $this->field->getFormattedValue($model2, $request));
    }

    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);

        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn(1);

        $resource = $this->makeMock(Resource::class, ['model']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        // form type
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('type="radio"', $this->field->getForm($request, $resource));

        // form name
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));

        // form value
        $this->assertStringContainsString('value="1"', $this->field->getForm($request, $resource));

        // form extra-param
        // $this->assertStringContainsString('extra-parm="extra-param-value"', $this->field->getForm($request, $resource, [ 'extra-param' => 'extra-param-value']));
    }
}
