<?php

namespace Tests\Unit;

use App\Acl\Usuario;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use PHPUnit\Framework\TestCase;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

function config(string $configParameter)
{
    return '';
}

class ValueTest extends TestCase
{
    protected $value;
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->value = new class() extends Value {
        };
    }


    public function testAttributes()
    {
        $this->assertObjectHasAttribute('dateFormat', $this->value);
        $this->assertObjectHasAttribute('prefix', $this->value);
        $this->assertObjectHasAttribute('suffix', $this->value);
    }

    public function testSum()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'input'])
            ->getMock();
        $request->expects($this->any())->method('get')->willReturn('valor');
        $request->expects($this->any())->method('input')->willReturn('MTD');

        $resource = $this->getMockBuilder('resource')
            ->setMethods(['model'])
            ->getMock();
        $resource->expects($this->any())->method('model')->willReturn('valor');

        $builder = $this->getMockBuilder(Builder::class)
            ->setMethods(['get'])
            ->getMock();
        $value->method('get')->willReturn('');

        $value = $this->getMockBuilder(Value::class)
            ->setMethods(['rangedQuery'])
            ->getMock();
        $value->method('rangedQuery')->willReturn(new Builder(new QueryBuilder));



        $this->assertEquals([], $this->value->sum($request, 'resource', 'columna', 'timecolumn'));
    }
}
