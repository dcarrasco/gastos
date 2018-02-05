<?php

use App\Stock\TipoAlmacenSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipoAlmacenSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipoAlmacenSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipoAlmacenSap::new());
    }
}
