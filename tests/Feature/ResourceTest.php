<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $resource;
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = factory(Usuario::class)->create();

        $this->resource = new class($this->model) extends Resource {
            public $model = 'App\Acl\Usuario';
            public $label = 'ResourceLabel';
            public $title = 'nombre';
            public $search = ['nombre', 'username'];
            public $orderBy = 'campo';
        };
    }

    public function testResourceSinModelo()
    {
        $this->expectException(\Exception::class);

        $this->classSinModelo = new class extends Resource {
        };
    }

    public function testGetName()
    {
        $this->resource = new \App\OrmModel\Acl\Usuario;

        $this->assertEquals('Usuario', $this->resource->getName());
    }

    public function testGetLabel()
    {
        $this->assertEquals('ResourceLabel', $this->resource->getLabel());

        $this->resource->label = '';
        $this->assertNotEquals('ResourceLabel', $this->resource->getLabel());
    }

    public function testGetLabelPlural()
    {
        $this->assertEquals('ResourceLabels', $this->resource->getLabelPlural());

        $this->resource->labelPlural = 'ResourceLabelPlural';
        $this->assertEquals('ResourceLabelPlural', $this->resource->getLabelPlural());
    }

    public function testTitle()
    {
        $this->assertEquals($this->model->nombre, $this->resource->title());
    }

    public function testModel()
    {
        $this->assertEquals($this->model, $this->resource->model());
    }

    public function testMakeModelInstance()
    {
        $this->assertEquals('', $this->resource->makeModelInstance()->nombre);
    }

    // =========================================================================
    // Trait UsesDatabase
    // =========================================================================

    public function testUrlSearchKey()
    {
        $this->assertEquals('search', $this->resource->urlSearchKey());
    }

    public function testGetModelQueryBuilder()
    {
        $this->assertInstanceOf(Builder::class, $this->resource->getModelQueryBuilder());
    }

    public function testApplySearchFilter()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['input', 'get'])
            ->getMock();

        $request->expects($this->any())->method('input')->willReturn('search');

        $this->assertStringContainsString('nombre', $this->resource->applySearchFilter($request)->getModelQueryBuilder()->toSql());
        $this->assertStringContainsString('username', $this->resource->applySearchFilter($request)->getModelQueryBuilder()->toSql());
    }


    public function testGetOrderBy()
    {
        $this->assertEquals(['campo' => 'asc'], $this->resource->getOrderBy());
    }

    public function testFindOrFail()
    {
        $this->assertEquals($this->model->nombre, $this->resource->findOrFail(1)->model()->nombre);

        $this->expectException(\Exception::class);
        $this->resource->findOrFail(2);
    }

    public function testFindOrNew()
    {
        $this->assertEquals($this->model->nombre, $this->resource->findOrNew(1)->model()->nombre);

        $this->assertEquals('', $this->resource->findOrNew(2)->model()->nombre);
    }

    public function testUpdate()
    {
        $nombreOriginal = $this->model->nombre;
        $nuevoNombre = 'nuevoNombre';

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $request->expects($this->any())->method('all')->willReturn(['nombre' => 'nuevoNombre']);

        $this->assertEquals($nombreOriginal, Usuario::first()->nombre);
        $this->assertEquals($nuevoNombre, $this->resource->update($request)->model()->nombre);
        $this->assertEquals($nuevoNombre, Usuario::first()->nombre);
    }

    // =========================================================================
    // Trait UsesFilters
    // =========================================================================


    public function testFilters()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsArray($this->resource->filters($request));
    }
}
