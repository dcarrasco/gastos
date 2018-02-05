<?php

use App\Stock\StockSapFija;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockSapFijaTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', StockSapFija::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) StockSapFija::new());
    }
}
