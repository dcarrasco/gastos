<?php

use App\Inventario\DetalleInventario;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DetalleInventarioTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', DetalleInventario::new());
    }

    public function testHasFields()
    {
        $this->assertNotEmpty(DetalleInventario::new()->getModelFields());
        $this->assertCount(21, DetalleInventario::new()->getModelFields());
    }

    public function testString()
    {
        $this->assertInternalType('string', (string) DetalleInventario::new());
    }
}
