<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Optional;
use App\OrmModel\src\OrmField\Text;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TextTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);

        $this->field = new class('nombreCampo') extends Text {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);

        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn('text value');

        $resource = $this->makeMock(Resource::class, ['model']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        // form type
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('type="text"', $this->field->getForm($request, $resource));

        //max length
        $this->assertStringContainsString('maxlength=250', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('maxlength=100', $this->field->rules('max:100')->getForm($request, $resource));

        // form name
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));

        // form value
        $this->assertStringContainsString('value="text value"', $this->field->getForm($request, $resource));

        //form readonly
        $model2 = $this->makeMock(Model::class, ['__get', 'getKey']);
        $model2->expects($this->any())->method('getKey')->willReturn('text value');
        $model2->expects($this->any())->method('__get')->willReturn('text value');
        $model2 = $model2->setKeyName('nombre_campo');

        $resource2 = $this->makeMock(Resource::class, ['model']);
        // $resource2->expects($this->any())->method('model')->willReturn($model2);
    }
}
