<?php

use App\Inventario\UnidadMedida;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnidadMedidaTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', UnidadMedida::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) UnidadMedida::new());
    }
}
