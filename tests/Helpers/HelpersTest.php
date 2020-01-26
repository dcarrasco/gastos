<?php

namespace Tests\Helpers;

use App\Helpers\Helpers;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelpersTest extends TestCase
{

    public function __testPrintMssage()
    {
        $this->assert_is_string(print_message('prueba'));
    }

    public function testFormArrayFormat()
    {
        $test = [
            ['llave' => 'a', 'valor' => '1', 'otro' => 'otro'],
            ['llave' => 'b', 'valor' => '2', 'otro' => 'otro'],
            ['llave' => 'c', 'valor' => '3', 'otro' => 'otro'],
        ];

        $this->assertEquals(formArrayFormat($test), ['a'=>'1', 'b'=>'2', 'c'=>'3']);
        $this->assertEquals(formArrayFormat($test, 'mensaje ini'), [''=>'mensaje ini', 'a'=>'1','b' =>'2', 'c'=>'3']);
    }

    public function testFmtCantidad()
    {
        $this->assertEquals(fmtCantidad(500), '500');
        $this->assertEquals(fmtCantidad(5000), '5.000');
        $this->assertEquals(fmtCantidad(5000.428, 2), '5.000,43');
        $this->assertEquals(fmtCantidad(0, 0, true), '0');
        $this->assertEquals(fmtCantidad(0, 0, false), '');
    }

    public function testFmtMonto()
    {
        $this->assertEquals(fmtMonto(500), '$&nbsp;500');
        $this->assertEquals(fmtMonto(5000), '$&nbsp;5.000');
        $this->assertEquals(fmtMonto(5000.428, 'UN', '$', 2), '$&nbsp;5.000,43');
        $this->assertEquals(fmtMonto(0, 'UN', '$', 0, true), '$&nbsp;0');
        $this->assertEquals(fmtMonto(0, 'UN', '$', 0, false), '');
        $this->assertEquals(fmtMonto(1222, 'UN', 'CLP'), 'CLP&nbsp;1.222');
        $this->assertEquals(fmtMonto(222123123, 'MM'), 'MM$&nbsp;222');
    }

    public function testFmtHora()
    {
        $this->assertEquals(fmtHora(33), '00:00:33');
        $this->assertEquals(fmtHora(60), '00:01:00');
        $this->assertEquals(fmtHora(133), '00:02:13');
        $this->assertEquals(fmtHora(3733), '01:02:13');
    }

    public function testFmtFecha()
    {
        $this->assertEquals(fmtFecha('20171011'), '2017-10-11');
        $this->assertEquals(fmtFecha('20171011', 'Y---m---d'), '2017---10---11');
    }

    public function testFmtFechaDb()
    {
        $this->assertEquals(fmtFechaDb('2017-10-11'), '20171011');
        $this->assertNotNull(fmtFechaDb());
    }

    public function testFmtRut()
    {
        $this->assertEquals(fmtRut('138889998'), '13.888.999-8');
    }

    public function testGetArrDiasMes()
    {
        $expected = [
            '01'=>null, '02'=>null, '03'=>null, '04'=>null, '05'=>null,
            '06'=>null, '07'=>null, '08'=>null, '09'=>null, 10=>null,
            11=>null, 12=>null, 13=>null, 14=>null, 15=>null,
            16=>null, 17=>null, 18=>null, 19=>null, 20=>null,
            21=>null, 22=>null, 23=>null, 24=>null, 25=>null,
            26=>null, 27=>null, 28=>null, 29=>null, 30=>null,
        ];

        $this->assertEquals(getArrDiasMes('201704'), $expected);
    }

    public function testGetFechaHasta()
    {
        $this->assertEquals(getFechaHasta('201703'), '20170401');
        $this->assertEquals(getFechaHasta('201612'), '20170101');
    }

    public function testDiaSemana()
    {
        $this->assertEquals(diaSemana(0), 'Do');
        $this->assertEquals(diaSemana(3), 'Mi');
        $this->assertEquals(diaSemana(6), 'Sa');
    }

    public function testClaseCumplimientoConsumos()
    {
        $this->assertEquals(clase_cumplimiento_consumos(.95), 'success');
        $this->assertEquals(clase_cumplimiento_consumos(.75), 'warning');
        $this->assertEquals(clase_cumplimiento_consumos(.45), 'danger');
    }
}
