<?php

namespace Tests\Unit\OrmModel\Filters;

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

        $this->filter = new class () extends Filter {
        };
    }

    public function testFilterHasParameterPrefix()
    {
        $this->assertClassHasAttribute('parameterPrefix', get_class($this->filter));
    }

    public function testApply()
    {
        $request = $this->createMock(Request::class);
        $query = $this->createMock(Builder::class);

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

    public function testGetOptionUrl()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn('valor');
        $request->expects($this->any())->method('all')->willReturn([]);
        $request->expects($this->any())->method('fullUrlWithQuery')->willReturn('');

        $this->assertEquals('', $this->filter->getOptionUrl($request, 'valor'));

        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('has')->willReturn(false);
        $request2->expects($this->any())->method('get')->willReturn('valor');
        $request2->expects($this->any())->method('all')->willReturn([]);
        $request2->expects($this->any())->method('fullUrlWithQuery')->willReturn('');

        $this->assertEquals('', $this->filter->getOptionUrl($request2, 'valor'));
    }

    public function testGetUrlParameter()
    {
        $this->assertStringContainsString('filter_', $this->filter->getUrlParameter());
    }

    public function testIsActive()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertTrue($this->filter->isActive($request, 'valor'));
        $this->assertFalse($this->filter->isActive($request, 'otroValor'));
    }

    public function testGetValue()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertEquals('valor', $this->filter->getValue($request));

        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('get')->willReturn(null);

        $this->assertEquals('', $this->filter->getValue($request2));
    }

    public function testIsSet()
    {
        // $request: OK has parameter; OK has value
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertTrue($this->filter->IsSet($request));

        // $request: OK has parameter; NOK has value
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(true);
        $request->expects($this->any())->method('get')->willReturn(null);

        $this->assertFalse($this->filter->IsSet($request));

        // $request: NOK has parameter; OK has value
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(false);
        $request->expects($this->any())->method('get')->willReturn('valor');

        $this->assertFalse($this->filter->IsSet($request));

        // $request: NOK has parameter; OK has value
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('has')->willReturn(false);
        $request->expects($this->any())->method('get')->willReturn(null);

        $this->assertFalse($this->filter->IsSet($request));
    }
}
