<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function testFmtCantidad()
    {
        $valor = 1234.567;

        $this->assertEquals('1.235', fmtCantidad($valor));
        $this->assertEquals('1.234,57', fmtCantidad($valor, 2));
        $this->assertEquals('1.234,5670', fmtCantidad($valor, 4));
        $this->assertEquals('', fmtCantidad('valor'));
    }

    public function testFmtMonto()
    {
        $valor = 1234.567;

        $this->assertEquals('$&nbsp;1.235', fmtMonto($valor)->toHtml());
        $this->assertEquals('', fmtMonto('valor')->toHtml());
    }
}
