<?php

use App\Helpers\Reporte;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReporteTest extends TestCase
{

    public function getReporte()
    {
        $datos = [
            (object) ['campo1' => 'valor_campo1', 'valor1' => 100, 'valor2' => 200],
            (object) ['campo1' => 'valor_campo2', 'valor1' => 300, 'valor2' => 400],
            (object) ['campo1' => 'valor_campo3', 'valor1' => 500, 'valor2' => 600],
        ];

        $campos = [
            'campo1' => ['titulo' => 'titulo_campo1'],
            'valor1' => ['titulo' => 'titulo_valor1', 'tipo' => 'numero'],
            'valor2' => ['titulo' => 'titulo_valor2', 'tipo' => 'numero'],
        ];

        return new Reporte($datos, $campos);
    }

    public function testGetOrderBy()
    {
        $this->assertEquals(
            'campo1 ASC, campo2 DESC, campo3 ASC',
            $this->getReporte()->getOrderBy('+campo1, -campo2, campo3')
        );

        $this->assertEquals('campo1 ASC', $this->getReporte()->getOrderBy('+campo1'));
        $this->assertEquals('campo1 DESC', $this->getReporte()->getOrderBy('-campo1'));
        $this->assertEquals('campo1 ASC', $this->getReporte()->getOrderBy('campo1'));
        $this->assertEquals(' ASC', $this->getReporte()->getOrderBy(''));
    }

    public function testResultToMonthTable()
    {
        $expected = ['llave' => [
            '01'=>null, '02'=>null, '03'=>null, '04'=>10,  '05'=>null,
            '06'=>20,   '07'=>null, '08'=>null, '09'=>null, 10=>null,
            11=>null, 12=>null, 13=>null, 14=>null, 15=>null,
            16=>30,   17=>null, 18=>null, 19=>null, 20=>null,
            21=>null, 22=>null, 23=>null, 24=>null, 25=>null,
            26=>null, 27=>null, 28=>null,
        ]];

        $this->assertEquals(
            $expected,
            $this->getReporte()->resultToMonthTable([
                ['fecha' => '20170204', 'dato' => 10, 'llave' => 'llave'],
                ['fecha' => '20170206', 'dato' => 20, 'llave' => 'llave'],
                ['fecha' => '20170216', 'dato' => 30, 'llave' => 'llave'],
            ])->all()
        );

        $expected = ['' => [
            '01'=>null, '02'=>null, '03'=>null, '04'=>null, '05'=>null,
            '06'=>null, '07'=>null, '08'=>null, '09'=>null, 10=>null,
            11=>null, 12=>null, 13=>null, 14=>null, 15=>null,
            16=>null, 17=>null, 18=>null, 19=>null, 20=>null,
            21=>null, 22=>null, 23=>null, 24=>null, 25=>null,
            26=>null, 27=>null, 28=>null,
        ]];

        $this->assertEquals(
            $expected,
            $this->getReporte()->resultToMonthTable([['campo1' => '20170204', 'campo2' => 10, 'campo3' => 'llave']])->all()
        );

    }

    public function testFormatoReporteTexto()
    {
        $reporte = $this->getReporte()->formatoReporte('casa', ['tipo' => 'texto']);

        $this->assertEquals('casa', $reporte);
    }

    public function testFormatoReporteFecha()
    {
        $reporte = $this->getReporte()->formatoReporte('20171001', ['tipo' => 'fecha']);

        $this->assertEquals('2017-10-01', $reporte);
    }

    public function testFormatoReporteNumero()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo' => 'numero']);

        $this->assertEquals('12.345', $reporte);
    }

    public function testFormatoReporteValor()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo' => 'valor']);

        $this->assertEquals('$&nbsp;12.345', $reporte);
    }

    public function testFormatoReporteValorPmp()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo' => 'valor']);

        $this->assertEquals('$&nbsp;12.345', $reporte);
    }

    public function testFormatoReporteNumeroDif()
    {
        $this->assertContains('+12.345', $this->getReporte()->formatoReporte(12345, ['tipo' => 'numero_dif']));
        $this->assertContains('-12.345', $this->getReporte()->formatoReporte(-12345, ['tipo' => 'numero_dif']));
    }

    public function testFormatoReporteLink()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo' => 'link', 'href' => 'http://a/b/c/']);

        $this->assertEquals('<a href="http://a/b/c/12345">12345</a>', $reporte);
    }

    public function __testFormatoReporteLinkRegistros()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo' => 'link_registro', 'href' => 'http://a/b/c', 'href_registros' => ['aa', 'bb', 'cc']], ['aa' => '11', 'bb' => '22', 'cc' => '33']);

        $this->assertEquals('<a href="http://a/b/c/11/22/33">12345</a>', $reporte);
    }

    public function testFormatoReporteLinkDetalleSeries()
    {
        $reporte = $this->getReporte()->formatoReporte(12345, ['tipo'=>'link_detalle_series', 'href'=>'http://a/b/c/'], ['centro'=>'CM11', 'almacen'=>'CH01', 'lote'=>'NUEVO', 'otro'=>'xx'], 'aa');

        $this->assertEquals('<a href="http://a/b/c/?centro=CM11&almacen=CH01&lote=NUEVO&permanencia=aa">12.345</a>', $reporte);
    }

    public function testFormatoReporteOtro()
    {
        $reporte = $this->getReporte()->formatoReporte('casa', ['tipo' => 'otrootro']);

        $this->assertEquals('casa', $reporte);
    }

    public function testFormatoReporteDoi()
    {
        $this->assertEquals(
            ' <i class="fa fa-circle text-danger"></i>',
            $this->getReporte()->formatoReporte(null, ['tipo' => 'doi'])
        );

        $this->assertEquals(
            '2,3 <i class="fa fa-circle text-success"></i>',
            $this->getReporte()->formatoReporte(2.3, ['tipo' => 'doi'])
        );

        $this->assertEquals(
            '20 <i class="fa fa-circle text-success"></i>',
            $this->getReporte()->formatoReporte(20, ['tipo' => 'doi'])
        );

        $this->assertEquals(
            '70 <i class="fa fa-circle text-warning"></i>',
            $this->getReporte()->formatoReporte(70, ['tipo' => 'doi'])
        );

        $this->assertEquals(
            '170 <i class="fa fa-circle text-danger"></i>',
            $this->getReporte()->formatoReporte(170, ['tipo' => 'doi'])
        );
    }

    public function testGeneraReporte()
    {
        $repo = $this->getReporte();
        $campos = $repo->getCampos();
        $repo->setOrderCampos($campos, 'campo1');
        $repo->setCampos($campos);

        $reporte = collect(explode(PHP_EOL, $repo->make()));

        $this->assertCount(3, $reporte->filter(function($linea) {return substr($linea, 0, 22)==='<td class="text-muted"';})->all());
        $this->assertCount(1, $reporte->filter(function($linea) {return substr($linea, 0, 6)==='<table';})->all());
        $this->assertCount(1, $reporte->filter(function($linea) {return substr($linea, 0, 6)==='<thead';})->all());
        $this->assertCount(3+2, $reporte->filter(function($linea) {return substr($linea, 0, 3)==='<tr';})->all());

        $this->assertNotEmpty(collect($repo->campos)->pluck('sort')->implode(''));
        $this->assertNotEmpty(collect($repo->campos)->pluck('img_orden')->implode(''));
    }


}

class Repo {

    // use \App\Helpers\Reporte;

    public $campos;

    public function getCamposReporte()
    {
        return ;
    }

    public function getDatosReporte()
    {
        return ;
    }

    public function getReporte()
    {
        $this->datos = $this->getDatosReporte();
        $this->campos = $this->getCamposReporte();

        $this->setOrderCampos($this->campos, 'campo1');

        return $this;
    }
}
