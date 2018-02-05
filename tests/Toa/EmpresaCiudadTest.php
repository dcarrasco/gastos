<?php

use App\Toa\EmpresaCiudad;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmpresaCiudadTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', EmpresaCiudad::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) EmpresaCiudad::new());
    }
}
