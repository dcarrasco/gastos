<?php

use App\Inventario\Almacen;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlmacenTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Almacen::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Almacen::new());
    }
}
