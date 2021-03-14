<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Date;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DateTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class ('nombreCampo') extends Date {
        };
    }

    public function testGetFormattedValue()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn(new Carbon('01-02-2020'));

        $this->assertEquals(
            new HtmlString('2020-02-01'),
            $this->field->resolveValue($model, $request)->getFormattedValue()
        );
    }

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn(new Carbon('01-02-2020'));

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        // form type
        $this->assertStringContainsString('<input', $this->field->getForm($request, $resource));
        $this->assertStringContainsString('type="date"', $this->field->getForm($request, $resource));

        // form name
        $this->assertStringContainsString('name="nombre_campo"', $this->field->getForm($request, $resource));

        // form value
        $this->assertStringContainsString('value="2020-02-01"', $this->field->getForm($request, $resource));
    }
}
