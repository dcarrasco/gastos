<?php

use App\Stock\ClaseMovimiento;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClaseMovimientoTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', ClaseMovimiento::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) ClaseMovimiento::new());
    }
}
