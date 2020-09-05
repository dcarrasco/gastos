<?php

namespace Tests\Unit\OrmModel\Metrics;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\Acl\App;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\Models\Acl\App as AppModel;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendTest extends TestCase
{
    use RefreshDatabase;

    protected $trend;
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->makeMock(Request::class, []);

        $this->trend = new class() extends Trend {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testCalculate()
    {
        $this->assertEmpty($this->trend->calculate($this->request));
    }

    public function testAggregators()
    {
        $app1 = factory(AppModel::class)->create(['orden' => 10]);
        $app2 = factory(AppModel::class)->create(['orden' => 20]);
        $app3 = factory(AppModel::class)->create(['orden' => 30]);
        $app4 = factory(AppModel::class)->create(['orden' => 40]);

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('MTD');

        $this->assertEquals(100, $this->trend->sumByDays($request, App::class, 'orden')->last());
        $this->assertEquals(100, $this->trend->sumByWeeks($request, App::class, 'orden')->last());
        $this->assertEquals(100, $this->trend->sumByMonths($request, App::class, 'orden')->last());
        $this->assertEquals(100, $this->trend->sumByYears($request, App::class, 'orden')->last());

        $this->assertEquals(4, $this->trend->countByDays($request, App::class)->last());
        $this->assertEquals(4, $this->trend->countByWeeks($request, App::class)->last());
        $this->assertEquals(4, $this->trend->countByMonths($request, App::class)->last());
        $this->assertEquals(4, $this->trend->countByYears($request, App::class)->last());
    }

    public function testContentScript()
    {
        $this->assertIsString($this->trend->contentScript($this->request)->toHtml());
    }
}
