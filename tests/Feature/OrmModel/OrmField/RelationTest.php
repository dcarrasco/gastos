<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class('nombreCampo', 'nombre_campo', 'modelClass') extends Relation {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testConstructor()
    {
        $this->assertEquals('nombreCampo', $this->field->getName());
        $this->assertEquals('nombre_campo', $this->field->getAttribute());
    }

    public function testRelationConditions()
    {
        $this->assertIsObject($this->field->relationConditions(['a'=>'b']));
    }

    public function testMake()
    {
        $this->assertIsObject($this->field->make('nombreCampo'));
    }

}
