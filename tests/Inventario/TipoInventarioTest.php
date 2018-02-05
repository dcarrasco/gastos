<?php

use App\Inventario\TipoInventario;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipoInventarioTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipoInventario::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipoInventario::new());
    }
}
