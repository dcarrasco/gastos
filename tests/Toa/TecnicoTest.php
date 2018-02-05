<?php

use App\Toa\Tecnico;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TecnicoTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Tecnico::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Tecnico::new());
    }
}
