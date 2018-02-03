<?php

use App\Inventario\Inventario;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InventarioTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', Inventario::create());
    }

    public function testHasFields()
    {
        $this->assertNotEmpty(Inventario::create()->getModelFields());
        $this->assertCount(4, Inventario::create()->getModelFields());
    }

    public function testString()
    {
        $this->assertInternalType('string', (string) Inventario::create());
    }

    public function testGetIdInventarioActivo()
    {
        $inventario = Inventario::create();

        app('db')->mock_set_return_result(['id'=>111]);
        $this->assert_equals(111, $inventario->getIdInventarioActivo());
    }

    public function testGetInventarioActivo()
    {
        $inventario = Inventario::create();

        app('db')->mock_set_return_result(['id'=>111, 'nombre' => 'prueba de inventario']);
        $this->assert_equals($inventario->get_inventario_activo()->__toString(), 'prueba de inventario');
    }

    public function testGetMaxHojaInventario()
    {
        $inventario = Inventario::create();

        app('db')->mock_set_return_result(['max_hoja'=>100]);
        $this->assert_equals($inventario->get_max_hoja_inventario(), 100);
    }

    public function testGetComboInventarios()
    {
        $inventario = Inventario::create();

        app('db')->mock_set_return_result([
            ['desc_tipo_inventario'=>'tipo1', 'id'=>1, 'nombre'=>'inventario1'],
            ['desc_tipo_inventario'=>'tipo1', 'id'=>2, 'nombre'=>'inventario2'],
            ['desc_tipo_inventario'=>'tipo2', 'id'=>3, 'nombre'=>'inventario3'],
        ]);
        $this->assert_equals($inventario->get_combo_inventarios(), [
            'tipo1' => [1 => 'inventario1', 2 => 'inventario2'],
            'tipo2' => [3 => 'inventario3'],
        ]);
    }
}
