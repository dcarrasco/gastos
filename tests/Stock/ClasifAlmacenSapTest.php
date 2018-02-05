<?php

use App\Stock\ClasifAlmacenSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClasifAlmacenSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', ClasifAlmacenSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) ClasifAlmacenSap::new());
    }
}
