<?php

use App\Stock\TipoClasifAlmacenSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipoClasifAlmacenSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipoClasifAlmacenSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipoClasifAlmacenSap::new());
    }
}
