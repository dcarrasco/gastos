<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterTest extends TestCase
{

    protected $filter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filter = new class() extends Filter {
        };
    }

    public function testFilterHasParameterPrefix()
    {
        $this->assertClassHasAttribute('parameterPrefix', get_class($this->filter));
    }

    public function testApply()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $query = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf(Builder::class, $this->filter->apply($request, $query, 'valor'));
    }

    public function testOptions()
    {
        $this->assertIsArray($this->filter->options());
    }

    public function testGetName()
    {
        $this->assertStringContainsString('FilterTest', $this->filter->getName());
    }

    public function testGetLabel()
    {
        $this->assertStringContainsString('filter test', $this->filter->getLabel());
    }

    public function testGetUrlParameter()
    {
        $this->assertStringContainsString('filter_FilterTest', $this->filter->getUrlParameter());
    }

    public function testIsActive()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertTrue($this->filter->isActive($request, 'valor'));
        $this->assertFalse($this->filter->isActive($request, 'otroValor'));
    }

    public function testGetValue()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertEquals('valor', $this->filter->getValue($request));

        $request2 = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $request2->expects($this->any())->method('get')->willReturn(null);
        $this->assertEquals('', $this->filter->getValue($request2));
    }

    public function testIsSet()
    {
        // $request: OK has parameter; OK has value
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'has'])
            ->getMock();
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertTrue($this->filter->IsSet($request));

        // $request: OK has parameter; NOK has value
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'has'])
            ->getMock();
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn(null);

        $this->assertFalse($this->filter->IsSet($request));

        // $request: NOK has parameter; OK has value
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'has'])
            ->getMock();
        $request->expects($this->any())->method('has')->willReturn(false);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertFalse($this->filter->IsSet($request));

        // $request: NOK has parameter; OK has value
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'has'])
            ->getMock();
        $request->expects($this->any())->method('has')->willReturn(false);
        $request->expects($this->any())->method('get')->willReturn(null);

        $this->assertFalse($this->filter->IsSet($request));
    }
}
