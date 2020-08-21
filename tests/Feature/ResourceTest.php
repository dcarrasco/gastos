<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
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

            public function fields(Request $request): array
            {
                return [
                    Id::make(),
                    Text::make('nombre')->rules('required'),
                    Text::make('username')->hideFromIndex()->rules('required', 'max:100'),
                ];
            }
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testResourceSinModelo()
    {
        $this->expectException(\Exception::class);

        $this->classSinModelo = new class extends Resource {
        };
    }

    public function testFields()
    {
        $request = $this->makeMock(Request::class, []);

        $resource = new class($this->model) extends Resource {
            public $model = 'App\Acl\Usuario';
        };

        $this->assertIsArray($resource->fields($request));
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

    public function testGetFields()
    {
        $this->assertEquals(collect([]), $this->resource->getFields());
    }

    public function testIndexFields()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertEquals(
            collect([$this->model->id, $this->model->nombre]),
            $this->resource->indexFields($request)->getFields()->map(function ($field) {
                return $field->value();
            })
        );
    }

    public function testDetailFields()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertEquals(
            collect([$this->model->id, $this->model->nombre, $this->model->username]),
            $this->resource->detailFields($request)->getFields()->map(function ($field) {
                return $field->value();
            })
        );
    }

    public function testFormFields()
    {
        $session = $this->makeMock(Request::class, ['get']);
        $session->expects($this->any())->method('get')->willReturn(null);

        $request = $this->makeMock(Request::class, ['session']);
        $request->expects($this->any())->method('session')->willReturn($session);

        $this->assertEquals(2, $this->resource->formFields($request)->getFields()->count());
    }

    public function testGetValidation()
    {
        $request = $this->makeMock(Request::class, ['session']);

        $this->assertEquals(['id' => '', 'nombre' => 'required', 'username' => 'required|max:100'], $this->resource->getValidation($request));
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
        $request2 = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['input', 'get'])
            ->getMock();

        $request2->expects($this->any())->method('input')->willReturn('');

        $this->assertStringNotContainsString('nombre', $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql());
        $this->assertStringNotContainsString('username', $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql());

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

    public function testResourceSetPerPage()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals(25, $this->resource->resourceSetPerpage($request)->model()->paginate()->perPage());

        $request2 = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['__get'])
            ->getMock();
        $request2->expects($this->any())->method('__get')->willReturn(100);

        $this->assertEquals(100, $this->resource->resourceSetPerpage($request2)->model()->paginate()->perPage());
    }

    public function testApplyOrderBy()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['has', 'input'])
            ->getMock();
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('input')->will($this->onConsecutiveCalls('nombre_campo', 'desc', 'nombre_campo', 'asc'));

        $this->assertStringContainsString('nombre_campo', $this->resource->applyOrderBy($request)->getModelQueryBuilder()->toSql());
        $this->assertStringContainsString('desc', $this->resource->applyOrderBy($request)->getModelQueryBuilder()->toSql());
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

    public function testCountAppliedFilters()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsInt($this->resource->countAppliedFilters($request));
        $this->assertEquals(0, $this->resource->countAppliedFilters($request));
    }


    // =========================================================================
    // Trait UsesCards
    // =========================================================================

    public function testCards()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsArray($this->resource->cards($request));
    }

    public function testRenderCards()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsObject($this->resource->renderCards($request));
        $this->assertCount(0, $this->resource->renderCards($request));
    }
}
