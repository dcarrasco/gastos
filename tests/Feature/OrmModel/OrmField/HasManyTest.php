<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
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
        $roles = factory(\App\Acl\Rol::class, 5)->create();
        $user = factory(\App\Acl\Usuario::class)->create();
        $user->rol()->sync($roles);

        $this->assertStringContainsString('<ul><li>', $this->field->getFormattedValue($user, $request));
    }

    public function testGetFormattedValueEmpty()
    {
        $request = $this->makeMock(Request::class, []);
        $roles = factory(\App\Acl\Rol::class, 5)->create();
        $user = factory(\App\Acl\Usuario::class)->create();

        $this->assertEquals('', $this->field->getFormattedValue($user, $request));
    }

    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);
        $roles = factory(\App\Acl\Rol::class, 3)->create();
        $user = factory(\App\Acl\Usuario::class)->create();
        $user->rol()->sync($roles);

        $userResource = new \App\OrmModel\Acl\Usuario($user);

        $this->assertStringContainsString('<select', $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[0]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[1]->rol, $this->field->getForm($request, $userResource));
        $this->assertStringContainsString($roles[2]->rol, $this->field->getForm($request, $userResource));
    }
}
