<?php

use App\Toa\Empresa;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmpresaTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Empresa::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Empresa::new());
    }
}
