<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class() extends Id {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testConstructor()
    {
        $this->assertEquals('id', $this->field->getName());

        $field2 = new class('nombreCampo') extends Id {
        };

        $this->assertEquals('nombreCampo', $field2->getName());
    }


    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);

        $model = $this->makeMock(Model::class, []);
        $model->expects($this->any())->method('__get')->willReturn('valor1');

        $resource = $this->makeMock(Resource::class, ['model']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertStringContainsString('<p', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('name="id"', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('value="valor1"', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('extra-param="extra-param-value"', $this->field->getForm($request, $resource, [
            'extra-param' => 'extra-param-value']));
    }
}
