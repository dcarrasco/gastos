<?php

use App\Inventario\Auditor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditorTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Auditor::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) Auditor::new());
    }
}
