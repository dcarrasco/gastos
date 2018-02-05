<?php

use App\Stock\AlmacenSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AlmacenSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', AlmacenSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) AlmacenSap::new());
    }
}
