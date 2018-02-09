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

    public function testGetterSetterName()
    {
        $this->assertEquals('test text', (new OrmField)->setName('test text')->getName());
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

    public function testGetterSetterChoices()
    {
        $this->assertEquals(
            ['op1'=>'1', 'op2'=>'2'],
            (new OrmField)->setChoices(['op1'=>'1', 'op2'=>'2'])->getChoices()
        );
    }

    public function testHasChoices()
    {
        $this->assertTrue((new OrmField)->setChoices(['op1'=>'1', 'op2'=>'2'])->hasChoices());
        $this->assertFalse((new OrmField)->setChoices([])->hasChoices());
    }

    public function testGetterSetterOnChange()
    {
        $this->assertEquals('test text', (new OrmField)->setOnChange('test text')->getOnChange());
    }

    public function testHasOnChange()
    {
        $this->assertTrue((new OrmField)->setOnChange('on change value')->hasOnChange());
        $this->assertFalse((new OrmField)->setOnChange('')->hasOnChange());
    }

    public function testGetterSetterParentModel()
    {
        $this->assertEquals('test text', (new OrmField)->setParentModel('test text')->getParentModel());
    }

    public function testGetterSetterRelationModel()
    {
        $this->assertEquals('test text', (new OrmField)->setRelationModel('test text')->getRelationModel());
    }

    public function testGetterSetterRelationConditions()
    {
        $this->assertEquals(
            ['op1'=>'1', 'op2'=>'2'],
            (new OrmField)->setRelationConditions(['op1'=>'1', 'op2'=>'2'])->getRelationConditions()
        );
    }

    public function testHasRelationConditions()
    {
        $this->assertTrue((new OrmField)->setRelationConditions(['op1'=>'1', 'op2'=>'2'])->hasRelationConditions());
        $this->assertFalse((new OrmField)->setRelationConditions([])->hasRelationConditions());
    }

    public function testGetterSetterEsObligatorio()
    {
        $this->assertTrue((new OrmField)->setEsObligatorio(true)->getEsObligatorio());
        $this->assertFalse((new OrmField)->setEsObligatorio(false)->getEsObligatorio());
    }

    public function testGetterSetterEsUnico()
    {
        $this->assertTrue((new OrmField)->setEsUnico(true)->getEsUnico());
        $this->assertFalse((new OrmField)->setEsUnico(false)->getEsUnico());
    }

    public function testGetterSetterEsId()
    {
        $this->assertTrue((new OrmField)->setEsId(true)->getEsId());
        $this->assertFalse((new OrmField)->setEsId(false)->getEsId());
    }

    public function testGetterSetterEsIncrementing()
    {
        $this->assertTrue((new OrmField)->setEsIncrementing(true)->getEsIncrementing());
        $this->assertFalse((new OrmField)->setEsIncrementing(false)->getEsIncrementing());
    }

    public function testGetValidation()
    {
        $this->assertEquals('', (new OrmField)->getValidation());
        $this->assertEquals('required', (new OrmField)->setEsObligatorio(true)->getValidation());
    }

    public function testGetRelatedModel()
    {
        $this->assertInternalType(
            'object',
            (new OrmField)->setRelationModel('App\OrmModel\OrmField')->getRelatedModel()
        );
        $this->assertEquals(
            'App\OrmModel\OrmField',
            get_class((new OrmField)->setRelationModel('App\OrmModel\OrmField')->getRelatedModel())
        );
        $this->assertEquals(
            'App\OrmModel\OrmModel',
            get_class(
                (new OrmField)->setRelationModel('App\OrmModel\OrmField')->getRelatedModel('App\OrmModel\OrmModel')
            )
        );
        $this->assertNull((new OrmField)->setRelationModel('')->getRelatedModel());
    }

    public function testGetFormattedValue()
    {
        $this->assertEquals('test text', (new OrmField)->getFormattedValue('test text'));
    }

    public function testGetForm()
    {
        $this->assertEquals(
            '<input id="testName" name="testName" type="text" value="test text">',
            (new OrmField)->setName('testName')->getForm('test text')
        );
    }
}
