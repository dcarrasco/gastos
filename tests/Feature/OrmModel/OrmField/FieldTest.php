<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
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

        view()->share('errors', new ViewErrorBag);

        $this->field = new class('nombreCampo') extends Field {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
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
        $resource = $this->makeMock(Resource::class, []);

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
        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn('valor');

        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);

        $this->assertNull($this->field->resolveValue($model, $request)->value());
    }

    public function testHasOnChange()
    {
        $this->assertFalse($this->field->hasOnChange());
        $this->assertTrue($this->field->onChange('test_string')->hasOnChange());
    }

    public function testResolveFormItem()
    {
        $session = $this->makeMock(Request::class, ['get']);
        $session->expects($this->any())->method('get')->willReturn([]);

        $request = $this->makeMock(Request::class, ['input', 'all', 'session']);
        $request->expects($this->any())->method('session')->willReturn($session);

        $resource = $this->makeMock(Resource::class, ['model', 'get']);

        $this->assertStringContainsString('input', $this->field->resolveFormItem($request, $resource)->formItem());
        $this->assertStringContainsString('type="text"', $this->field->resolveFormItem($request, $resource)->formItem());
        $this->assertStringContainsString('name="nombre_campo"', $this->field->resolveFormItem($request, $resource)->formItem());
    }

    public function testFormItem()
    {
        $this->assertEquals('', $this->field->formItem());
    }

    public function testGetFormattedValue()
    {
        $model = $this->makeMock(Model::class, ['__get']);
        $model->expects($this->any())->method('__get')->willReturn('valor');

        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);

        $this->assertNull($this->field->getFormattedValue($model, $request));
    }

    public function testValue()
    {
        $this->assertEquals('', $this->field->value());
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
        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())->method('input')->willReturn('');

        $resource = $this->makeMock(Resource::class, ['input', 'get']);

        $this->assertEquals('', $this->field->makeSortingIcon($request, $resource)->sortingIcon());
        $this->assertStringContainsString('url', $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon());
        // default icon class
        $this->assertStringContainsString('fa fa-sort text-gray-400', $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon());
    }

    public function testMakeSortingIconAsc()
    {
        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())->method('input')->will($this->onConsecutiveCalls('nombre_campo', 'asc', 'nombre_campo', 'asc'));

        $resource = $this->makeMock(Resource::class, ['input', 'get']);

        // default icon class
        $this->assertStringContainsString('fa-caret-up', $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon());
    }

    public function testMakeSortingIconDown()
    {
        $request = $this->makeMock(Request::class, ['input', 'all', 'fullUrlWithQuery']);
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');
        $request->expects($this->any())->method('input')->will($this->onConsecutiveCalls('nombre_campo', 'desc', 'nombre_campo', 'desc'));

        $resource = $this->makeMock(Resource::class, ['input', 'get']);

        // default icon class
        $this->assertStringContainsString('fa-caret-down', $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon());
    }

    // -------------------------------------------------------------------------
    // UsesValidation
    // -------------------------------------------------------------------------

    public function testGetValidation()
    {
        $model = $this->makeMock(Model::class, ['getTable', 'getKey']);
        $model->expects($this->any())->method('getTable')->willReturn('table_name');
        $model->expects($this->any())->method('getKey')->willReturn('value');

        $resource = $this->makeMock(Resource::class, ['model']);
        $resource->expects($this->any())->method('model')->willReturn($model);

        $this->assertEquals('required|unique:table_name,nombre_campo,value,id|max:10', $this->field->rules('required', 'unique', 'max:10')->getValidation($resource));
    }
}
