<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Query\Builder as QueryBuilder;
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
            public $model = 'App\Models\Acl\Usuario';
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
            public $model = 'App\Models\Acl\Usuario';
        };

        $this->assertIsArray($resource->fields($request));
        $this->assertEmpty($resource->fields($request));
    }


    public function testGetName()
    {
        $this->resource = new \App\OrmModel\Acl\Usuario;

        $this->assertEquals('Usuario', $this->resource->getName());
    }

    public function testGetLabel()
    {
        $this->assertEquals('ResourceLabel', $this->resource->getLabel());

        $this->resource->label = 'AnotherLabel';
        $this->assertNotEquals('ResourceLabel', $this->resource->getLabel());
        $this->assertEquals('AnotherLabel', $this->resource->getLabel());
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

        $this->resource->title = 'id';
        $this->assertEquals(1, $this->resource->title());
    }

    public function testModel()
    {
        $this->assertEquals($this->model, $this->resource->model());
    }

    public function testMakeModelInstance()
    {
        $this->assertEquals('', $this->resource->makeModelInstance()->nombre);
        $this->assertNotEquals($this->model, $this->resource->makeModelInstance());
        $this->assertEquals('App\Models\Acl\Usuario', get_class($this->resource->makeModelInstance()));
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

    public function testModelFormOptions()
    {
        $request = $this->makeMock(Request::class, ['all']);
        $request->expects($this->any())->method('all')->willReturn(['elem' => ['a'=>1, 'b'=>2]]);

        $user1 = factory(Usuario::class)->make();
        $user2 = factory(Usuario::class)->make();

        $builder = $this->makeMock(QueryBuilder::class, ['get']);
        $builder->expects($this->any())->method('get')->willReturn(collect([$user1, $user2]));

        $this->assertIsObject($this->resource->getModelFormOptions($request));
        $this->assertEquals('', $this->resource->getModelAjaxFormOptions($request));
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
        $request2 = $this->makeMock(Request::class, ['input', 'get']);
        $request2->expects($this->any())->method('input')->willReturn('');

        $this->assertStringNotContainsString('nombre', $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql());
        $this->assertStringNotContainsString('username', $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql());

        $request = $this->makeMock(Request::class, ['input', 'get']);
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
        $request = $this->makeMock(Request::class, []);
        $this->assertEquals(25, $this->resource->resourceSetPerpage($request)->model()->paginate()->perPage());

        $request2 = $this->makeMock(Request::class, ['__get']);
        $request2->expects($this->any())->method('__get')->willReturn(100);

        $this->assertEquals(100, $this->resource->resourceSetPerpage($request2)->model()->paginate()->perPage());
    }

    public function testApplyOrderBy()
    {
        $request = $this->makeMock(Request::class, ['has', 'input']);
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

        $request = $this->makeMock(Request::class, ['all']);
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

    public function testApplyFilters()
    {
        $this->model = factory(Usuario::class)->create();

        $this->resource = new class($this->model) extends Resource {
            public $model = 'App\Models\Acl\Usuario';
            public function filters(Request $request): array
            {
                return [
                    new class() extends Filter {
                    }
                ];
            }
        };

        $request = $this->makeMock(Request::class, ['has', 'get']);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertIsObject($this->resource->applyFilters($request));
    }

    public function testCountAppliedFilters()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertIsInt($this->resource->countAppliedFilters($request));
        $this->assertEquals(0, $this->resource->countAppliedFilters($request));
    }

    // =========================================================================
    // Trait UsesCards
    // =========================================================================

    public function testCards()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertIsArray($this->resource->cards($request));
    }

    public function testRenderCards()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertIsObject($this->resource->renderCards($request));
        $this->assertCount(0, $this->resource->renderCards($request));
    }

    // =========================================================================
    // Trait PaginatesResources
    // =========================================================================

    public function testGetPaginator()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertIsObject($this->resource->paginator($request));
        $this->assertIsObject($this->resource->getPaginator($request));
    }

    public function testMakePaginatedResources()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertIsObject($this->resource->makePaginatedResources($request));
        $this->assertIsObject($this->resource->resourceList($request));
    }

    public function testPaginationLinksDetail()
    {
        $this->assertFalse($this->resource->paginationLinksDetail());
    }
}
