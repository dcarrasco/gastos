<?php

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrmModelTest extends TestCase
{
    protected function getModel()
    {
        return new ModelTest([
            'campo1' => 'valor_campo1',
            'campo2' => '111',
            'campo3' => true,
        ]);
    }

    public function testNew()
    {
        $this->assertInternalType('object', ModelTest::new());
    }

    public function testGetModelFields()
    {
        $fields = collect([
            'id' => new OrmField([
                'name' => 'id',
                'tipo' => OrmField::TIPO_ID,
                'parentModel' => 'ModelTest',
            ]),
            'campo1' => new OrmField([
                'name' => 'campo1',
                'label' => 'Label campo1',
                'tipo' => OrmField::TIPO_CHAR,
                'largo' => 50,
                'textoAyuda' => 'Ayuda campo1',
                'parentModel' => 'ModelTest',
                'esObligatorio' => true,
                'esUnico' => true
            ]),
            'campo2' => new OrmField([
                'name' => 'campo2',
                'label' => 'Label campo2',
                'tipo' => OrmField::TIPO_INT,
                'textoAyuda' => 'Ayuda campo2',
                'parentModel' => 'ModelTest',
            ]),
            'campo3' => new OrmField([
                'name' => 'campo3',
                'label' => 'Label campo3',
                'tipo' => OrmField::TIPO_BOOLEAN,
                'textoAyuda' => 'Ayuda campo3',
                'parentModel' => 'ModelTest',
            ]),
        ]);

        $this->assertEquals($fields, $this->getModel()->getModelFields());
    }

    public function testGetFieldLabel()
    {
        $this->assertEquals('Label campo1', $this->getModel()->getFieldLabel('campo1'));
        $this->assertEquals('', $this->getModel()->getFieldLabel('campo_xx'));
        $this->assertEquals('', $this->getModel()->getFieldLabel());
    }

    public function testGetFieldsList()
    {
        $this->assertEquals(['campo1', 'campo2', 'campo3'], $this->getModel()->getFieldsList());
    }

    public function testGetFieldHelp()
    {
        $this->assertEquals('Ayuda campo1', $this->getModel()->getFieldHelp('campo1'));
        $this->assertEmpty($this->getModel()->getFieldHelp('campo_xx'));
        $this->assertEmpty($this->getModel()->getFieldHelp());
    }

    public function testGetFieldType()
    {
        $this->assertEquals(OrmField::TIPO_CHAR, $this->getModel()->getFieldType('campo1'));
        $this->assertEquals(OrmField::TIPO_INT, $this->getModel()->getFieldType('campo2'));
        $this->assertEquals(OrmField::TIPO_BOOLEAN, $this->getModel()->getFieldType('campo3'));
        $this->assertEmpty($this->getModel()->getFieldType('campo_xx'));
        $this->assertEmpty($this->getModel()->getFieldType());
    }

    public function testGetFieldLength()
    {
        $this->assertEquals(50, $this->getModel()->getFieldLength('campo1'));
        $this->assertNull($this->getModel()->getFieldLength('campo2'));
        $this->assertNull($this->getModel()->getFieldLength('campo3'));
        $this->assertNull($this->getModel()->getFieldLength('campo_xx'));
        $this->assertNull($this->getModel()->getFieldLength());
    }

    public function testGetFormattedFieldValue()
    {
        $this->assertEquals('valor_campo1', $this->getModel()->getFormattedFieldValue('campo1'));
        $this->assertEquals(111, $this->getModel()->getFormattedFieldValue('campo2'));
        $this->assertEquals(trans('orm.radio_yes'), $this->getModel()->getFormattedFieldValue('campo3'));
        $this->assertNull($this->getModel()->getFormattedFieldValue('campo_xx'));
        $this->assertNull($this->getModel()->getFormattedFieldValue());
    }

    public function testIsFieldMandatory()
    {
        $this->assertTrue($this->getModel()->isFieldMandatory('campo1'));
        $this->assertFalse($this->getModel()->isFieldMandatory('campo2'));
        $this->assertFalse($this->getModel()->isFieldMandatory('campo3'));
        $this->assertFalse($this->getModel()->isFieldMandatory('campo_xx'));
        $this->assertFalse($this->getModel()->isFieldMandatory());
    }

    public function testGetValidation()
    {
        $this->assertEquals(
            [
                'id' => '',
                'campo1' => 'required|max:50',
                'campo2' => 'integer',
                'campo3' => '',
            ],
            $this->getModel()->getValidation()
        );
    }
}

class ModelTest extends OrmModel
{
    public $modelLabel = 'LabelTest';

    public $modelOrder = 'OrderTest';

    protected $fillable = ['campo1', 'campo2', 'campo3'];

    protected $casts = [
        'campo2' => 'integer',
        'campo3' => 'boolean',
    ];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'campo1' => [
            'label' => 'Label campo1',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Ayuda campo1',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'campo2' => [
            'label' => 'Label campo2',
            'tipo' => OrmField::TIPO_INT,
            'textoAyuda' => 'Ayuda campo2',
        ],
        'campo3' => [
            'label' => 'Label campo3',
            'tipo' => OrmField::TIPO_BOOLEAN,
            'textoAyuda' => 'Ayuda campo3',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'table_model_test';
    }

}
