<?php

use App\Stock\StockSapMovil;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockSapMovilTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', StockSapMovil::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) StockSapMovil::new());
    }
}
