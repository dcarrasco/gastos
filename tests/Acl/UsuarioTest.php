<?php

use App\Acl\Usuario;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsuarioTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Usuario::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Usuario::new());
    }
}
