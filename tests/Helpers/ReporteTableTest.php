<?php

use App\Helpers\ReporteTable;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReporteTableTest extends TestCase
{
    public function testNew()
    {
        $this->assertInternalType('object', new ReporteTable);
    }

    public function __testTemplate()
    {
        $reporteTable = new ReporteTable;

        $this->assertInternalType('array', $reporteTable->template);
    }
}
