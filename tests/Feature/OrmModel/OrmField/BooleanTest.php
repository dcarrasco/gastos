<?php

namespace Tests\Feature\OrmModel\OrmField;

use App\OrmModel\src\OrmField\Boolean;
use App\OrmModel\src\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BooleanTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class('nombreCampo') extends Boolean
        {
        };
    }

    public function testGetFormattedValue()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn(1);

        $this->assertStringContainsString('span', $this->field->resolveValue($model, $request)->getFormattedValue());
        $this->assertStringContainsString('green', $this->field->resolveValue($model, $request)->getFormattedValue());

        $model2 = $this->createMock(Model::class);
        $model2->expects($this->any())->method('getAttribute')->willReturn(0);
        $this->assertStringContainsString('red', $this->field->resolveValue($model2, $request)->getFormattedValue());
    }

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('1');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $model2 = $this->createMock(Model::class);
        $model2->expects($this->any())->method('getAttribute')->willReturn('0');

        $resource2 = $this->createMock(Resource::class);
        $resource2->expects($this->any())->method('model')->willReturn($model2);

        // form type
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('type="radio"', $this->field->getForm($request, $resource));

        // form name
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));

        // form value
        $this->assertStringContainsString('value="1" checked', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('value="0" checked', $this->field->getForm($request, $resource2));

        // form extra-param
        // $this->assertStringContainsString(
        //     'extra-parm="extra-param-value"',
        //     $this->field->getForm($request, $resource, [ 'extra-param' => 'extra-param-value'])
        // );
    }
}
