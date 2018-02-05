<?php

use App\Stock\MovimientoSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovimientoSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', MovimientoSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) MovimientoSap::new());
    }
}
