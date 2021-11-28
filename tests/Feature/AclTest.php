<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\App;
use App\Models\Acl\Rol;
use App\Models\Acl\Modulo;
use App\Models\Acl\Usuario;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AclTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function __testMenuApp()
    {
        $usuario = Usuario::factory()->create();
        $app = App::factory()->create();
        $rol = Rol::factory()->create(['app_id' => 1]);
        $modulos = Modulo::factory(5)->create(['app_id' => 1]);

        $usuario->rol()->attach([1]);
        $rol->modulo()->attach([1,2,3,4,5]);

        $route = $this->createMock(\Illuminate\Routing\Route::class);
        $route->expects($this->any())->method('getName')->willReturn('name');

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn($route);

        $urlGenerator = $this->createMock(UrlGenerator::class);
        $urlGenerator->expects($this->any())->method('route')->willReturn('aaa');
        // dump($urlGenerator->route('ss'));
        // dump(app('url'));

        $menuApp = $usuario->getMenuApp($request);

        $this->assertEquals(
            $menuApp->pluck('llave_modulo')->sort()->values(),
            $modulos->pluck('llave_modulo')->sort()->values()
        );
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
}
