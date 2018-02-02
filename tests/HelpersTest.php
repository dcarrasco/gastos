<?php

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
        $this->assertEquals(fmtCantidad(0, 0, TRUE), '0');
        $this->assertEquals(fmtCantidad(0, 0, FALSE), '');
    }

    public function testFmtMonto()
    {
        $this->assertEquals(fmtMonto(500), '$&nbsp;500');
        $this->assertEquals(fmtMonto(5000), '$&nbsp;5.000');
        $this->assertEquals(fmtMonto(5000.428, 'UN', '$', 2), '$&nbsp;5.000,43');
        $this->assertEquals(fmtMonto(0, 'UN', '$', 0, TRUE), '$&nbsp;0');
        $this->assertEquals(fmtMonto(0, 'UN', '$', 0, FALSE), '');
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
        $this->assertNull(fmtFechaDb());
    }

    public function testFmtRut()
    {
        $this->assertEquals(fmtRut('138889998'), '13.888.999-8');

    }

    public function test_get_arr_dias_mes()
    {
        $expected = [
            '01' => NULL, '02' => NULL, '03' => NULL, '04' => NULL, '05' => NULL,
            '06' => NULL, '07' => NULL, '08' => NULL, '09' => NULL, 10 => NULL,
            11 => NULL, 12 => NULL, 13 => NULL, 14 => NULL, 15 => NULL,
            16 => NULL, 17 => NULL, 18 => NULL, 19 => NULL, 20 => NULL,
            21 => NULL, 22 => NULL, 23 => NULL, 24 => NULL, 25 => NULL,
            26 => NULL, 27 => NULL, 28 => NULL, 29 => NULL, 30 => NULL,
        ];

        $this->assertEquals(get_arr_dias_mes('201704'), $expected);
    }

    public function test_get_fecha_hasta_mismo_anno()
    {
        $this->assertEquals(get_fecha_hasta('201703'), '20170401');
        $this->assertEquals(get_fecha_hasta('201612'), '20170101');
    }

    public function test_dias_de_la_semana()
    {
        $this->assertEquals(dias_de_la_semana(0), 'Do');
        $this->assertEquals(dias_de_la_semana(3), 'Mi');
        $this->assertEquals(dias_de_la_semana(6), 'Sa');
    }

    public function test_clase_cumplimiento_consumos()
    {
        $this->assertEquals(clase_cumplimiento_consumos(.95), 'success');
        $this->assertEquals(clase_cumplimiento_consumos(.75), 'warning');
        $this->assertEquals(clase_cumplimiento_consumos(.45), 'danger');
    }


}
