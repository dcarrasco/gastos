<?php

use App\Toa\Ciudad;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CiudadTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Ciudad::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Ciudad::new());
    }
}
