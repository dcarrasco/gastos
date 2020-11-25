<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use App\Models\Acl\Rol;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasManyTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);

        $this->field = new class('nombreCampo', 'rol', \App\OrmModel\Acl\Rol::class) extends HasMany {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testConstructor()
    {
        $this->assertFalse($this->field->showOnIndex());
    }

    public function testGetFormattedValue()
    {
        $request = $this->makeMock(Request::class, []);
        $roles = Rol::factory(5)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $this->assertStringContainsString('<ul><li>', $this->field->getFormattedValue($user, $request));
    }

    public function testGetFormattedValueWithAttributes()
    {
        $request = $this->makeMock(Request::class, []);
        $roles = Rol::factory(5)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $this->field->relationField('abilities', '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}');

        $this->assertStringContainsString('<table', $this->field->getFormattedValue($user, $request));
    }

    public function testGetFormattedValueEmpty()
    {
        $request = $this->makeMock(Request::class, []);
        $roles = Rol::factory(5)->create();
        $user = Usuario::factory()->create();

        $this->assertEquals('', $this->field->getFormattedValue($user, $request));
    }

    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);
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
        $request = $this->makeMock(Request::class, []);
        $roles = Rol::factory(3)->create();
        $user = Usuario::factory()->create();
        $user->rol()->sync($roles);

        $userResource = new \App\OrmModel\Acl\Usuario($user);

        $this->field->relationField('abilities', '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}');

        $this->assertStringContainsString('<table', $this->field->getForm($request, $userResource));
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
