<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Acl\App;
use App\Models\Acl\Rol;
use App\Models\Acl\Modulo;
use App\Models\Acl\Usuario;
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
        $usuario = Usuario::factory()->create();
        $app = App::factory()->create();
        $rol = Rol::factory()->create(['app_id' => 1]);
        $modulos = Modulo::factory(5)->create(['app_id' => 1]);

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
