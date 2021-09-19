<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\MessageBag;
use App\OrmModel\src\OrmField\Field;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FieldTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class ('nombreCampo') extends Field {
        };
    }

    public function testMake()
    {
        $this->assertIsObject($this->field->make());
    }

    public function testHideFromIndex()
    {
        $this->assertTrue($this->field->showOnIndex());
        $this->assertFalse($this->field->hideFromIndex()->showOnIndex());
    }

    public function testHideFromDetail()
    {
        $this->assertTrue($this->field->showOnDetail());
        $this->assertFalse($this->field->hideFromDetail()->showOnDetail());
    }

    public function testHideFromForm()
    {
        $this->assertTrue($this->field->showOnForm());
        $this->assertFalse($this->field->hideFromForm()->showOnForm());
    }

    public function testAlignOnList()
    {
        $this->assertEquals('text-left', $this->field->alignOnList());
    }

    public function testGetName()
    {
        $this->assertEquals('nombreCampo', $this->field->getName());
    }

    public function testSetName()
    {
        $this->assertEquals('nuevoNombre', $this->field->setName('nuevoNombre')->getName());
    }

    public function testGetAttribute()
    {
        $this->assertEquals('nombre_campo', $this->field->getAttribute());
    }

    public function testGetModelAttribute()
    {
        $resource = $this->createMock(Resource::class);

        $this->assertEquals('nombre_campo', $this->field->getModelAttribute($resource));
    }

    public function testIsRequired()
    {
        $this->assertFalse($this->field->rules('rule1', 'rule2', 'rule3', 'rule4')->isRequired());
        $this->assertFalse($this->field->rules(['rule1', 'rule2', 'rule3', 'rule4'])->isRequired());
        $this->assertTrue($this->field->rules('rule1', 'rule2', 'rule3', 'required')->isRequired());
        $this->assertTrue($this->field->rules(['rule1', 'rule2', 'rule3', 'required'])->isRequired());
    }

    public function testGetOnChange()
    {
        $this->assertEquals('', $this->field->getOnChange());
    }

    public function testOnChange()
    {
        $this->assertEquals('test_string', $this->field->onChange('test_string')->getOnChange());
    }

    public function testResolveValue()
    {
        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor');

        $request = $this->createMock(Request::class);

        $this->assertEquals(new HtmlString('valor'), $this->field->resolveValue($model, $request)->value());
    }

    public function testHasOnChange()
    {
        $this->assertFalse($this->field->hasOnChange());
        $this->assertTrue($this->field->onChange('test_string')->hasOnChange());
    }

    public function testResolveFormItem()
    {
        $session = $this->createMock(Request::class);
        $session->expects($this->any())->method('get')->willReturn([]);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('session')->willReturn($session);

        $resource = $this->createMock(Resource::class);

        $this->assertStringContainsString('input', $this->field->resolveFormItem($request, $resource)->formItem());
        $this->assertStringContainsString(
            'type="text"',
            $this->field->resolveFormItem($request, $resource)->formItem()
        );
        $this->assertStringContainsString(
            'name="nombre_campo"',
            $this->field->resolveFormItem($request, $resource)->formItem()
        );
    }

    public function testFormItem()
    {
        $this->assertEquals('', $this->field->formItem());
    }

    public function testGetFormattedValue()
    {
        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn('valor');

        $request = $this->createMock(Request::class);

        $this->assertEquals(new HtmlString('valor'), $this->field->resolveValue($model, $request)->getFormattedValue());
    }

    public function testValue()
    {
        $this->assertEquals('', $this->field->value());
    }

    public function testSetPlaceholder()
    {
        $request = $this->createMock(Request::class);
        $resource = $this->createMock(Resource::class);

        $this->assertStringContainsString(
            'placeholder="newPlaceholder"',
            $this->field->placeholder('newPlaceholder')->getForm($request, $resource)
        );
    }


    public function testEagerLoadsRelation()
    {
        $this->assertFalse($this->field->eagerLoadsRelation());
    }

    // -------------------------------------------------------------------------
    // UsesSorting
    // -------------------------------------------------------------------------

    public function testSortingIcon()
    {
        $this->assertEquals('', $this->field->sortingIcon());
    }

    public function testMakeSortingIcon()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())->method('input')->willReturn('');

        $resource = $this->createMock(Resource::class);

        $this->assertEquals('', $this->field->makeSortingIcon($request, $resource)->sortingIcon());
        $this->assertStringContainsString(
            'url',
            $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon()
        );
        // default icon class
        $this->assertStringContainsString(
            'fa fa-sort text-gray-400',
            $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon()
        );
    }

    public function testMakeSortingIconAsc()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())
            ->method('input')
            ->will($this->onConsecutiveCalls('nombre_campo', 'asc', 'nombre_campo', 'asc'));

        $resource = $this->createMock(Resource::class, ['input', 'get']);

        // default icon class
        $this->assertStringContainsString(
            'fa-caret-up',
            $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon()
        );
    }

    public function testMakeSortingIconDown()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())
            ->method('input')
            ->will($this->onConsecutiveCalls('nombre_campo', 'desc', 'nombre_campo', 'desc'));

        $resource = $this->createMock(Resource::class);

        // default icon class
        $this->assertStringContainsString(
            'fa-caret-down',
            $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon()
        );
    }

    // -------------------------------------------------------------------------
    // UsesValidation
    // -------------------------------------------------------------------------

    public function testGetValidation()
    {
        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getTable')->willReturn('table_name');
        $model->expects($this->any())->method('getKey')->willReturn('value');
        $model->expects($this->any())->method('getKeyName')->willReturn('id');

        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertEquals(
            ['required', 'unique:table_name,nombre_campo,value,id', 'max:10'],
            $this->field->rules('required', 'unique', 'max:10')->getValidation($resource)
        );
    }

    public function testHasErrors()
    {
        $errors_true = collect(['nombre_campo' => 'Error campo1']);
        $errors_false = collect(['otro_nombre_campo' => 'Error campo1']);
        $resource = $this->createMock(Resource::class);

        $this->assertTrue($this->field->hasErrors($errors_true, $resource));
        $this->assertFalse($this->field->hasErrors($errors_false, $resource));
    }

    public function testGetErrors()
    {
        $resource = $this->createMock(Resource::class);

        $errors = $this->createMock(MessageBag::class);
        $errors->expects($this->any())->method('first')->willReturn('Error campo 1');

        $this->assertEquals('Error campo 1', $this->field->getErrors($errors, $resource));
    }

}
