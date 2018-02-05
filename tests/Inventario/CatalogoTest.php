<?php

use App\Inventario\Catalogo;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CatalogoTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Catalogo::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Catalogo::new());
    }
}
