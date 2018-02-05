<?php

use App\Inventario\Centro;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CentroTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Centro::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Centro::new());
    }
}
