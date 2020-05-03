<?php

namespace Tests\Feature;

use App\Acl\App;
use App\Acl\Rol;
use App\Acl\Modulo;
use Tests\TestCase;
use App\Acl\Usuario;
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
    public function testMenuApp()
    {
        $usuario = factory(Usuario::class)->create();
        $app = factory(App::class)->create();
        $rol = factory(Rol::class)->create(['app_id' => 1]);
        $modulos = factory(Modulo::class, 5)->create(['app_id' => 1]);

        $usuario->rol()->attach([1]);
        $rol->modulo()->attach([1,2,3,4,5]);

        $menuApp = $usuario->getMenuApp();

        $this->assertEquals(
            $menuApp->pluck('llave_modulo')->sort()->values(),
            $modulos->pluck('llave_modulo')->sort()->values()
        );
    }

    public function testUsuarioHasPassword()
    {
        $usuario1 = factory(Usuario::class)->create();
        $usuario2 = factory(Usuario::class)->create(['password' => '']);

        $this->assertTrue($usuario1->hasPassword());
        $this->assertFalse($usuario2->hasPassword());
    }

    public function testUsuarioCheckPassword()
    {
        $usuario = factory(Usuario::class)->create(['password' => bcrypt('secret')]);

        $this->assertTrue($usuario->checkPassword('secret'));
        $this->assertFalse($usuario->checkPassword('not-secret'));
    }

    public function testUsuarioStorePassword()
    {
        $usuario = factory(Usuario::class)->create(['password' => bcrypt('secret')]);
        $passwordOriginal = $usuario->password;

        $usuario->storePassword('new-secret');
        $passwordNueva = $usuario->password;

        $this->assertNotEquals($passwordOriginal, $passwordNueva);
    }
}
