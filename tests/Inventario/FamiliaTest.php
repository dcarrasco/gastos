<?php

use App\Inventario\Familia;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FamiliaTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Familia::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Familia::new());
    }
}
