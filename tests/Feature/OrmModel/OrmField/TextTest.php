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

        view()->share('errors', new ViewErrorBag());

        $this->field = new class ('nombreCampo') extends Text {
        };
    }

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('text value');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        // form type
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('type="text"', $this->field->getForm($request, $resource));

        //max length
        $this->assertStringContainsString('maxlength=250', $this->field->getForm($request, $resource));
        $this->assertStringContainsString(
            'maxlength=100',
            $this->field->rules('max:100')->getForm($request, $resource)
        );

        // form name
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));

        // form value
        $this->assertStringContainsString('value="text value"', $this->field->getForm($request, $resource));

        //form readonly
        $model2 = $this->createMock(Model::class);
        $model2->expects($this->any())->method('getKey')->willReturn('text value');
        $model2->expects($this->any())->method('getAttribute')->willReturn('text value');
        $model2 = $model2->setKeyName('nombre_campo');
    }
}
