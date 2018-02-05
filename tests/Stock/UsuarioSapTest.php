<?php

use App\Stock\UsuarioSap;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsuarioSapTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', UsuarioSap::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) UsuarioSap::new());
    }
}
