<?php

use App\Inventario\DetalleInventario;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DetalleInventarioTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', DetalleInventario::create());
    }

    public function testHasFields()
    {
        $this->assertNotEmpty(DetalleInventario::create()->getModelFields());
        $this->assertCount(21, DetalleInventario::create()->getModelFields());
    }

    public function testString()
    {
        $this->assertInternalType('string', (string) DetalleInventario::create());
    }
}
