<?php

use App\Acl\Modulo;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModuloTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Modulo::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Modulo::new());
    }
}
