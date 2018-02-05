<?php

use App\OrmModel\OrmField;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrmFieldTest extends TestCase
{
    public function testNew()
    {
        $this->assertInternalType('object', new OrmField);
    }

    public function testGetterSetterLabel()
    {
        $this->assertEquals('test text', (new OrmField)->setLabel('test text')->getLabel());
    }

    public function testGetterSetterTipo()
    {
        $this->assertEquals('test text', (new OrmField)->setTipo('test text')->getTipo());
    }

    public function testGetterSetterLargo()
    {
        $this->assertEquals('test text', (new OrmField)->setLargo('test text')->getLargo());
    }

    public function testGetterSetterTextoAyuda()
    {
        $this->assertEquals('test text', (new OrmField)->setTextoAyuda('test text')->getTextoAyuda());
    }

    public function testGetterSetterMostrarLista()
    {
        $this->assertTrue((new OrmField)->setMostrarLista(true)->getMostrarLista());
        $this->assertFalse((new OrmField)->setMostrarLista(false)->getMostrarLista());
    }

    public function testGetterSetterEsObligatorio()
    {
        $this->assertEquals(true, (new OrmField)->setEsObligatorio(true)->getEsObligatorio());
        $this->assertEquals(false, (new OrmField)->setEsObligatorio(false)->getEsObligatorio());
    }

    public function testGetterSetterEsUnico()
    {
        $this->assertEquals(true, (new OrmField)->setEsUnico(true)->getEsUnico());
        $this->assertEquals(false, (new OrmField)->setEsUnico(false)->getEsUnico());
    }
}
