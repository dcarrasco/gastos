<?php

use App\Acl\Rol;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RolTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Rol::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Rol::new());
    }
}
