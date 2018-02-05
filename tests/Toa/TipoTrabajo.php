<?php

use App\Toa\TipoTrabajo;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipoTrabajoTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipoTrabajo::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipoTrabajo::new());
    }
}
