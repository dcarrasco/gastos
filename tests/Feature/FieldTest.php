<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Field;
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

        $this->field = new class('nombreCampo') extends Field {
        };
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

    public function testIsRequired()
    {
        $this->assertFalse($this->field->rules('rule1', 'rule2', 'rule3', 'rule4')->isRequired());
        $this->assertTrue($this->field->rules('rule1', 'rule2', 'rule3', 'required')->isRequired());
    }

    public function testGetOnChange()
    {
        $this->assertEquals('', $this->field->getOnChange());
    }

    public function testOnChange()
    {
        $this->assertEquals('test_string', $this->field->onChange('test_string')->getOnChange());
    }

    public function testHasOnChange()
    {
        $this->assertFalse($this->field->hasOnChange());
        $this->assertTrue($this->field->onChange('test_string')->hasOnChange());
    }

    public function testGetFormattedValue()
    {
        $model = $this->getMockBuilder(Model::class)
            ->disableOriginalConstructor()
            ->setMethods(['__get'])
            ->getMock();

        $model->expects($this->any())->method('__get')->willReturn('valor');

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['input', 'all', 'fullUrlWithQuery'])
            ->getMock();

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
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['input', 'all', 'fullUrlWithQuery'])
            ->getMock();

        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('url');

        $resource = $this->getMockBuilder(Resource::class)
            ->disableOriginalConstructor()
            ->setMethods(['input', 'get'])
            ->getMock();

        $this->assertEquals('', $this->field->makeSortingIcon($request, $resource)->sortingIcon());
        $this->assertStringContainsString('url', $this->field->sortable()->makeSortingIcon($request, $resource)->sortingIcon());
    }
}
