<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\Textarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TextareaTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class ('nombreCampo') extends Textarea {
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

        $model = $this->makeMock(Model::class, []);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor1');

        $resource = $this->makeMock(Resource::class, ['model']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertStringContainsString('<textarea', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));
        $this->assertStringContainsString(
            'maxlength=100',
            $this->field->rules('max:100')->getForm($request, $resource)
        );
        $this->assertStringContainsString('valor1</textarea>', $this->field->getForm($request, $resource));
    }
}
