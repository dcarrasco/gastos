<?php

namespace Tests\Feature\OrmModel\OrmField;

use App\Models\Acl\Modulo;
use App\Models\Acl\Rol;
use App\Models\Acl\Usuario;
use App\OrmModel\src\OrmField\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class HasManyTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class('nombreCampo', 'rol', \App\OrmModel\Acl\Rol::class) extends HasMany
        {
        };
    }

    public function testConstructor()
    {
        $this->assertFalse($this->field->showOnIndex());
    }

    public function testGetFormattedValue()
    {
        $request = $this->createMock(Request::class);
        $roles = Rol::factory(5)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $this->assertStringContainsString('<ul><li>', $this->field->resolveValue($user, $request)->getFormattedValue());
    }

    public function testGetFormattedValueWithAttributes()
    {
        $request = $this->createMock(Request::class);
        $modulos = Modulo::factory(5)->create();
        $rol = Rol::factory()->create();
        $rol->modulo()->sync($modulos);

        $field = new class('nombreCampo', 'modulo', \App\OrmModel\Acl\Modulo::class) extends HasMany
        {
        };
        $field->relationField(
            'abilities',
            '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}'
        );

        $rol->modulo[0]->pivot->abilities = collect(['view-any']);

        $this->assertStringContainsString('<table', $field->resolveValue($rol, $request)->getFormattedValue());
        $this->assertStringContainsString('checked', $field->getFormattedValue());
    }

    public function testGetFormattedValueEmpty()
    {
        $request = $this->createMock(Request::class);
        $roles = Rol::factory(5)->create();
        $user = Usuario::factory()->create();

        $this->assertEquals('', $this->field->resolveValue($user, $request)->getFormattedValue());
    }

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);
        $roles = Rol::factory(3)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $userResource = new \App\OrmModel\Acl\Usuario($user);

        $this->assertStringContainsString('<select', $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[0]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[1]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[2]->rol, $this->field->getForm($request, $userResource));
    }

    public function testGetFormWithAttributes()
    {
        $request = $this->createMock(Request::class);
        $roles = Rol::factory(3)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $userResource = new \App\OrmModel\Acl\Usuario($user);

        $this->field->relationField(
            'abilities',
            '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}'
        );

        $this->assertStringContainsString(
            '<table',
            $this->field->resolveValue($user, $request)->getForm($request, $userResource)
        );
        $this->assertStringContainsString('<select', $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[0]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[1]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[2]->rol, $this->field->getForm($request, $userResource));
    }

    public function testGetRelationFields()
    {
        $this->assertIsArray($this->field->getRelationFields());
    }
}
