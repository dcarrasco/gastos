<?php

use App\Inventario\TipoUbicacion;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipoUbicacionTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipoUbicacion::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipoUbicacion::new());
    }
}
