<?php

namespace Tests\Feature\OrmModel\Metrics;

use App\Models\Acl\App as AppModel;
use App\OrmModel\Acl\App;
use App\OrmModel\src\Metrics\Trend;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TrendTest extends TestCase
{
    use RefreshDatabase;

    protected $trend;

    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Request::class);

        $this->trend = new class() extends Trend
        {
        };
    }

    public function testCalculate()
    {
        $this->assertEmpty($this->trend->calculate($this->request));
    }

    public function testAggregators()
    {
        $app1 = AppModel::factory()->create(['orden' => 10]);
        $app2 = AppModel::factory()->create(['orden' => 20]);
        $app3 = AppModel::factory()->create(['orden' => 30]);
        $app4 = AppModel::factory()->create(['orden' => 40]);

        $request = $this->createMock(Request::class);
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

    public function testContent()
    {
        $this->assertIsString($this->trend->content($this->request)->toHtml());
    }

    public function testContentAjaxRequest()
    {
        $this->assertIsArray($this->trend->contentAjaxRequest($this->request));
    }

    public function testContentScript()
    {
        $this->assertIsString($this->trend->contentScript($this->request)->toHtml());
    }
}
