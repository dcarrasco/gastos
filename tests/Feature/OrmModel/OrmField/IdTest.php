<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use Illuminate\Support\ViewErrorBag;
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

        view()->share('errors', new ViewErrorBag());

        $this->field = new class () extends Id {
        };
    }

    public function testConstructor()
    {
        $this->assertEquals('id', $this->field->getName());

        $field2 = new class ('nombreCampo') extends Id {
        };

        $this->assertEquals('nombreCampo', $field2->getName());
    }

    public function testGetFormEsIncrementing()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor1');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertStringContainsString('<p', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('name="id"', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('value="valor1"', $this->field->getForm($request, $resource));
    }

    public function testGetFormNoEsIncrementing()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor1');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->field->esIncrementing(false);

        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('name="id"', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('value="valor1"', $this->field->getForm($request, $resource));
    }
}
