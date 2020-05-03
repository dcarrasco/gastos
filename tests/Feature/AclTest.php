<?php

namespace Tests\Feature;

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
    public function testExample()
    {
        $user = factory(Usuario::class)->create();
        dump($user);

        $this->assertTrue(true);
    }
}
