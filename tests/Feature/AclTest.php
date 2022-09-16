<?php

namespace Tests\Feature;

use App\Models\Acl\App;
use App\Models\Acl\Modulo;
use App\Models\Acl\Rol;
use App\Models\Acl\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AclTest extends TestCase
{
    use RefreshDatabase;

    protected function creaUsuarioConMenu(): Usuario
    {
        $usuario = Usuario::factory()->create();

        $app = App::factory()->create();
        $rol = Rol::factory()->create();
        $modulo1 = Modulo::factory()->create(['url' => 'modulo-url1']);
        $modulo2 = Modulo::factory()->create(['url' => 'modulo-url2']);

        $rol->app()->associate($app);
        $rol->save();

        $modulo1->app()->associate($app);
        $modulo1->save();

        $modulo2->app()->associate($app);
        $modulo2->save();

        $rol->modulo()->attach($modulo1, ['abilities' => '["view", "create"]']);
        $rol->modulo()->attach($modulo2, ['abilities' => '["delete"]']);

        $usuario->rol()->attach($rol);

        return $usuario;
    }

    public function testMenuApp()
    {
        $usuario = $this->creaUsuarioConMenu();

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn(new class
        {
            public function getName()
            {
                return 'modulo-url1';
            }
        });

        $menuApp = $usuario->getMenuApp($request);

        $this->assertEquals(2, $menuApp->count());
        $this->assertEquals(['modulo-url1', 'modulo-url2'], $menuApp->pluck('url')->sort()->values()->all());
        $this->assertEquals(1, $menuApp->filter(fn ($modulo) => $modulo->selected)->count());
    }

    public function testUsuarioHasPassword()
    {
        $usuario1 = Usuario::factory()->create();
        $usuario2 = Usuario::factory()->create(['password' => '']);

        $this->assertTrue($usuario1->hasPassword());
        $this->assertFalse($usuario2->hasPassword());
    }

    public function testUsuarioCheckPassword()
    {
        $usuario = Usuario::factory()->create(['password' => bcrypt('secret')]);

        $this->assertTrue($usuario->checkPassword('secret'));
        $this->assertFalse($usuario->checkPassword('not-secret'));
    }

    public function testUsuarioStorePassword()
    {
        $usuario = Usuario::factory()->create(['password' => bcrypt('secret')]);
        $passwordOriginal = $usuario->password;

        $usuario->storePassword('new-secret');
        $passwordNueva = $usuario->password;

        $this->assertNotEquals($passwordOriginal, $passwordNueva);
    }

    public function testUsuarioHasAbilities()
    {
        $usuario = $this->creaUsuarioConMenu();

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn(new class
        {
            public function getName()
            {
                return 'modulo-url1';
            }
        });

        $this->assertTrue($usuario->hasAbility('view', $request));
        $this->assertTrue($usuario->hasAbility('create', $request));
        $this->assertFalse($usuario->hasAbility('delete', $request));

        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('route')->willReturn(new class
        {
            public function getName()
            {
                return 'modulo-url2';
            }
        });

        $this->assertFalse($usuario->hasAbility('view', $request2));
        $this->assertFalse($usuario->hasAbility('create', $request2));
        $this->assertTrue($usuario->hasAbility('delete', $request2));
    }
}
