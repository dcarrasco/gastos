<?php

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;
use App\OrmModel\OrmField\OrmFieldInt;
use App\OrmModel\OrmField\OrmFieldChar;
use App\OrmModel\OrmField\OrmFieldBoolean;

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

    public function testInitFields()
    {
        $model = new OrmModel();
        $model->initFields(['campo2' => [
            'tipo' => OrmField::TIPO_CHAR,
        ]]);

        $this->assertInternalType('object', $model);
        $this->assertCount(1, $model->getModelFields());
    }

    public function testNew()
    {
        $this->assertInternalType('object', ModelTest::new());
    }

    public function testScopeFiltroOrm()
    {
        $this->assertInternalType('object', $this->getModel()->filtroOrm());
        $this->assertInternalType('object', $this->getModel()->filtroOrm('filtro'));
    }

    public function testScopeModelOrderBy()
    {
        $this->assertInternalType('object', $this->getModel()->modelOrderBy());
    }

    public function testGetModelFields()
    {
        $fields = collect([
            'id' => new OrmFieldInt([
                'name' => 'id',
                'label' => 'ID',
                'tipo' => OrmField::TIPO_ID,
                'parentModel' => 'ModelTest',
                'isId' => true,
                'isIncrementing' => true,
            ]),
            'campo1' => new OrmFieldChar([
                'name' => 'campo1',
                'label' => 'Label campo1',
                'tipo' => OrmField::TIPO_CHAR,
                'largo' => 50,
                'textoAyuda' => 'Ayuda campo1',
                'parentModel' => 'ModelTest',
                'esObligatorio' => true,
                'esUnico' => true
            ]),
            'campo2' => new OrmFieldInt([
                'name' => 'campo2',
                'label' => 'Label campo2',
                'tipo' => OrmField::TIPO_INT,
                'textoAyuda' => 'Ayuda campo2',
                'parentModel' => 'ModelTest',
            ]),
            'campo3' => new OrmFieldBoolean([
                'name' => 'campo3',
                'label' => 'Label campo3',
                'tipo' => OrmField::TIPO_BOOLEAN,
                'textoAyuda' => 'Ayuda campo3',
                'parentModel' => 'ModelTest',
            ]),
        ]);

        $this->assertEquals($fields, $this->getModel()->getModelFields());
    }

    public function testGetRelatedModel()
    {
        $this->assertNull($this->getModel()->getRelatedModel('campo1'));
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

    public function testGetFieldForm()
    {
        $this->assertNotEmpty($this->getModel()->getFieldForm('campo1'));
        $this->assertContains('input', (string) $this->getModel()->getFieldForm('campo1'));
        $this->assertContains('campo1', (string) $this->getModel()->getFieldForm('campo1'));

        $this->assertNotEmpty($this->getModel()->getFieldForm('campo2'));
        $this->assertContains('input', (string) $this->getModel()->getFieldForm('campo2'));
        $this->assertContains('campo2', (string) $this->getModel()->getFieldForm('campo2'));

        $this->assertNotEmpty($this->getModel()->getFieldForm('campo3'));
        $this->assertContains('input', (string) $this->getModel()->getFieldForm('campo3'));
        $this->assertContains('campo3', (string) $this->getModel()->getFieldForm('campo3'));
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

    public $modelOrder = 'campo1';

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
