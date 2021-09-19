<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\Rol;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Filters\Filter;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\HasMany;
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

        view()->share('errors', new ViewErrorBag());

        $this->model = Usuario::factory()->create();

        $this->resource = new class ($this->model) extends Resource {
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

    public function testResourceSinModelo()
    {
        $this->expectException(\Exception::class);

        $this->classSinModelo = new class extends Resource {
        };
    }

    public function testFields()
    {
        $request = $this->createMock(Request::class);

        $resource = new class ($this->model) extends Resource {
            public $model = 'App\Models\Acl\Usuario';
        };

        $this->assertIsArray($resource->fields($request));
        $this->assertEmpty($resource->fields($request));
    }


    public function testGetName()
    {
        $this->resource = new \App\OrmModel\Acl\Usuario();

        $this->assertEquals('Usuario', $this->resource->getName());
    }

    public function testGetLabel()
    {
        $this->assertEquals('ResourceLabel', $this->resource->getLabel());

        $this->resource->label = 'AnotherLabel';
        $this->assertNotEquals('ResourceLabel', $this->resource->getLabel());
        $this->assertEquals('AnotherLabel', $this->resource->getLabel());

        $this->resource->label = '';
        $this->assertEquals($this->resource->getName(), $this->resource->getLabel());
        $this->assertStringContainsString('ResourceTest.php:', $this->resource->getLabel());
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
        $request = $this->createMock(Request::class);
        $expected = collect([
            new HtmlString($this->model->id),
            new HtmlString($this->model->nombre),
        ]);

        $this->assertEquals(
            $expected,
            $this->resource
                ->resolveIndexFields($request)
                ->getFields()
                ->map(fn($field) => $field->getFormattedValue())
        );
    }

    public function testDetailFields()
    {
        $request = $this->createMock(Request::class);
        $expected = collect([
            new HtmlString($this->model->id),
            new HtmlString($this->model->nombre),
            new HtmlString($this->model->username),
        ]);

        $this->assertEquals(
            $expected,
            $this->resource
                ->resolveDetailFields($request)
                ->getFields()
                ->map(fn($field) => $field->getFormattedValue())
        );
    }

    public function testFormFields()
    {
        $request = $this->createMock(Request::class);

        $this->assertEquals(2, $this->resource->resolveFormFields($request)->getFields()->count());
    }

    public function testGetValidation()
    {
        $request = $this->createMock(Request::class);

        $this->assertEquals(
            [
                'id' => [],
                'nombre' => ['required'],
                'username' => ['required', 'max:100'],
            ],
            $this->resource->getValidation($request)
        );
    }

    public function testModelFormOptions()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn(['id' => [1, 2]]);

        $user2 = Usuario::factory()->create();
        $user3 = Usuario::factory()->create();
        $usuarioResource = new \App\OrmModel\Acl\Usuario();

        $this->assertTrue($usuarioResource->getModelFormOptions($request)->has(1));
        $this->assertTrue($usuarioResource->getModelFormOptions($request)->has(2));
        $this->assertFalse($usuarioResource->getModelFormOptions($request)->has(3));

        $this->assertStringContainsString($this->model->nombre, $this->resource->getModelAjaxFormOptions($request));
        $this->assertStringContainsString($user2->nombre, $this->resource->getModelAjaxFormOptions($request));
        $this->assertStringNotContainsString($user3->nombre, $this->resource->getModelAjaxFormOptions($request));
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
        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('input')->willReturn('');

        $this->assertStringNotContainsString(
            'nombre',
            $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql()
        );
        $this->assertStringNotContainsString(
            'username',
            $this->resource->applySearchFilter($request2)->getModelQueryBuilder()->toSql()
        );

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('input')->willReturn('search');

        $this->assertStringContainsString(
            'nombre',
            $this->resource->applySearchFilter($request)->getModelQueryBuilder()->toSql()
        );
        $this->assertStringContainsString(
            'username',
            $this->resource->applySearchFilter($request)->getModelQueryBuilder()->toSql()
        );
    }

    public function testGetOrderBy()
    {
        $this->assertEquals(['campo' => 'asc'], $this->resource->getOrderBy());
    }

    public function testResourceSetPerPage()
    {
        $request = $this->createMock(Request::class);
        $this->assertEquals(25, $this->resource->resourceSetPerPage($request)->model()->paginate()->perPage());

        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('input')->willReturn(100);

        $this->assertEquals(100, $this->resource->resourceSetPerPage($request2)->model()->paginate()->perPage());
    }

    public function testApplyOrderBy()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())
            ->method('input')
            ->will($this->onConsecutiveCalls('nombre_campo', 'desc', 'nombre_campo', 'asc'));

        $this->assertStringContainsString(
            'nombre_campo',
            $this->resource->applyOrderBy($request)->getModelQueryBuilder()->toSql()
        );
        $this->assertStringContainsString(
            'desc',
            $this->resource->applyOrderBy($request)->getModelQueryBuilder()->toSql()
        );
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

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn(['nombre' => 'nuevoNombre']);

        $this->assertEquals($nombreOriginal, Usuario::first()->nombre);
        $this->assertEquals($nuevoNombre, $this->resource->update($request)->model()->nombre);
        $this->assertEquals($nuevoNombre, Usuario::first()->nombre);
    }

    public function testUpdateHasMany()
    {
        $roles = Rol::factory(4)->create();

        $resource = new class ($this->model) extends Resource {
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
                    HasMany::make('rol', 'rol', Rol::class),
                ];
            }
        };

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn(['nombre' => 'nuevoNombre']);
        $request->expects($this->any())->method('input')
            ->with($this->equalTo('rol'))
            ->willReturn($this->onConsecutiveCalls([1, 2], [3, 4]));

        $this->assertEquals(0, Usuario::first()->rol->count());

        $resource->update($request);
        $this->assertEquals([1, 2], Usuario::first()->rol->modelKeys());

        $resource->update($request);
        $this->assertEquals([3, 4], Usuario::first()->rol->modelKeys());
    }

    public function testUpdateHasManyWithAttributes()
    {
        $rol = \App\Models\Acl\Rol::factory()->create();
        $modulos = \App\Models\Acl\Modulo::factory(2)->create();

        $rolResource = new \App\OrmModel\Acl\Rol($rol);

        $this->assertEquals(0, Rol::first()->modulo->count());

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('all')->willReturn(['rol' => 'nuevoNombre']);
        $request->expects($this->any())->method('input')->will($this->returnValueMap([
            ['modulo', null, [1, 2]],
            ['__delete-model__', null, null],
            ['attributes:abilities:1', [], ['m1a', 'm1b']],
            ['attributes:abilities:2', [], ['m2a']],
        ]));

        $rolResource->update($request);
        $this->assertEquals(2, Rol::first()->modulo->count());
        $this->assertEquals('["m1a","m1b"]', Rol::first()->modulo->first()->pivot->abilities);
        $this->assertEquals('["m2a"]', Rol::first()->modulo->last()->pivot->abilities);
    }

    // =========================================================================
    // Trait UsesFilters
    // =========================================================================

    public function testFilters()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsArray($this->resource->filters($request));
    }

    public function testApplyFilters()
    {
        $this->model = Usuario::factory()->create();

        $this->resource = new class ($this->model) extends Resource {
            public $model = 'App\Models\Acl\Usuario';
            public function filters(Request $request): array
            {
                return [
                    new class () extends Filter {
                    }
                ];
            }
        };

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertIsObject($this->resource->applyFilters($request));
    }

    public function testCountAppliedFilters()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsInt($this->resource->countAppliedFilters($request));
        $this->assertEquals(0, $this->resource->countAppliedFilters($request));
    }

    // =========================================================================
    // Trait UsesCards
    // =========================================================================

    public function testCards()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsArray($this->resource->cards($request));
    }

    public function testRenderCards()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsObject($this->resource->renderCards($request));
        $this->assertCount(0, $this->resource->renderCards($request));
    }

    // =========================================================================
    // Trait PaginatesResources
    // =========================================================================

    public function testGetPaginator()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsObject($this->resource->paginator($request));
        $this->assertIsObject($this->resource->getPaginator($request));
    }

    public function testMakePaginatedResources()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsObject($this->resource->makePaginatedResources($request));
        $this->assertIsObject($this->resource->resourceList($request));
    }

    public function testPaginationLinksDetail()
    {
        $this->assertFalse($this->resource->paginationLinksDetail());
    }

    public function testGetRouteControllerId()
    {
        [$resourceName, $resourceId] = $this->resource->getRouteControllerId();

        $this->assertTrue(str_starts_with($resourceName, 'ResourceTest.php:'));
        $this->assertEquals(1, $resourceId);
    }

    public function testDeleteMessage()
    {
        $this->assertStringContainsString('ResourceLabel', $this->resource->deleteMessage());
        $this->assertStringContainsString($this->model->nombre, $this->resource->deleteMessage());
    }
}
