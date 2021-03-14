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

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor1');

        $resource = $this->createMock(Resource::class);
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
