<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use App\Models\Acl\App;
use App\Models\Acl\Rol;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class ('nombreCampo', 'nombre_campo', \App\OrmModel\Acl\App::class) extends Relation {
        };
    }

    public function testConstructor()
    {
        $this->assertEquals('nombreCampo', $this->field->getName());
        $this->assertEquals('nombre_campo', $this->field->getAttribute());
    }

    public function testRelationConditions()
    {
        $this->assertIsObject($this->field->relationConditions(['a' => 'b']));
    }

    public function testMake()
    {
        $this->assertIsObject($this->field->make('nombreCampo'));
    }

    public function testGetRelationOptions()
    {
        $request = $this->createMock(Request::class);
        $apps = App::factory(3)->create();

        $this->assertIsObject($this->field->getRelationOptions($request, new \App\OrmModel\Acl\Modulo()));
        $this->assertCount(3, $this->field->getRelationOptions($request, new \App\OrmModel\Acl\Modulo()));
        $this->assertEquals(
            $apps->pluck('app', 'id')->sort()->all(),
            $this->field->getRelationOptions($request, new \App\OrmModel\Acl\Modulo())->all()
        );
    }

    public function testGetRelationOptionsWithRelationFilter()
    {
        $request = $this->createMock(Request::class);

        $rol = new class (Rol::factory()->create()) extends Resource {
            public string $model = 'App\Models\Acl\Rol';
            public string $labelPlural = 'Roles';
            public string $icono = 'server';
            public string $title = 'rol';
            public array $search = [
                'id', 'rol', 'descripcion'
            ];
            public $orderBy = [
                'app_id' => 'asc', 'rol' => 'asc'
            ];

            public function fields(Request $request): array
            {
                return [
                    \App\OrmModel\src\OrmField\HasMany::make('modulo', 'modulo', \App\OrmModel\Acl\Modulo::class)
                        ->relationConditions(['app_id' => '@field_value:app_id:NULL'])
                ];
            }
        };

        view()->share('errors', new ViewErrorBag());

        $this->assertIsObject($rol->resolveFormFields($request));
    }
}
