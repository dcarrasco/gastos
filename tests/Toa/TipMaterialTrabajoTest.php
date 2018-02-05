<?php

use App\Toa\TipMaterialTrabajo;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TipMaterialTrabajoTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', TipMaterialTrabajo::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) TipMaterialTrabajo::new());
    }
}
