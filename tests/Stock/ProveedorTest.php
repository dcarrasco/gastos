<?php

use App\Stock\Proveedor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProveedorTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Proveedor::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Proveedor::new());
    }
}
