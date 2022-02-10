<?php

namespace Tests\Feature\OrmModel\Metrics;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Metrics\Metric;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetricTest extends TestCase
{
    use RefreshDatabase;

    /** @var Metric */
    protected $metric;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metric = new class () extends Metric {
            public function getCurrentRange(Request $request): array
            {
                return $this->currentRange($request);
            }

            public function getPreviousRange(Request $request): array
            {
                return $this->previousRange($request);
            }

            public function contentScript(Request $request): HtmlString
            {
                return new HtmlString('<script>test</script>');
            }
        };
    }

    public function currentRangeDataProvider()
    {
        return [
            ['MTD', now()->startOfMonth(), now()],
            ['QTD', now()->startOfQuarter(), now()],
            ['YTD', now()->startOfYear(), now()],
            ['CURR_MONTH', now()->startOfMonth(), now()->endOfMonth()],
            ['LAST_MONTH', now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            [10, now()->subDays(10-1), now()],
        ];
    }

    public function previousRangeDataProvider()
    {
        return [
            ['MTD', now()->subMonth()->startOfMonth(), now()->subMonth()],
            ['QTD', now()->subQuarter()->subMonth()->startOfQuarter(), now()->subQuarter()],
            ['YTD', now()->subYear()->subMonth()->startOfYear(), now()->subYear()],
            ['CURR_MONTH', now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            ['LAST_MONTH', now()->subMonth()->subMonth()->startOfMonth(), now()->subMonth()->subMonth()->endOfMonth()],
            [10, now()->subDays(20-1), now()->subDays(10)],
        ];
    }

    public function testMakeMetric()
    {
        $this->assertStringContainsString('MetricTest.php:', class_basename($this->metric->make()));
    }

    /**
     * @dataProvider currentRangeDataProvider
     */
    public function testGetCurrentRange($currentRange, $startDate, $endDate)
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('input')->willReturn($currentRange);

        $this->assertEquals(
            [$startDate->toDateString(), $endDate->toDateString()],
            [
                $this->metric->getCurrentRange($request)[0]->toDateString(),
                $this->metric->getCurrentRange($request)[1]->toDateString()
            ]
        );
    }

    /**
     * @dataProvider previousRangeDataProvider
     */
    public function testGetPreviousRange($previousRange, $startDate, $endDate)
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('input')->willReturn($previousRange);

        $this->assertEquals(
            [$startDate->toDateString(), $endDate->toDateString()],
            [
                $this->metric->getPreviousRange($request)[0]->toDateString(),
                $this->metric->getPreviousRange($request)[1]->toDateString()
            ]
        );
    }

    public function testMetricContent()
    {
        $request = $this->createMock(Request::class);

        $this->assertInstanceOf(HtmlString::class, $this->metric->content($request));
        $this->assertStringContainsString('<div class', $this->metric->content($request)->__toString());
        $this->assertStringContainsString('</div>', $this->metric->content($request)->__toString());
    }

    public function testContentScript()
    {
        $request = $this->createMock(Request::class);

        $this->assertInstanceOf(HtmlString::class, $this->metric->contentScript($request));
        $this->assertEquals('<script>test</script>', $this->metric->contentScript($request));
    }

    public function testRanges()
    {
        $this->assertIsArray($this->metric->ranges());
    }

    public function testUriKey()
    {
        $this->assertIsString($this->metric->uriKey());
        $this->assertStringContainsString('metric-test', $this->metric->uriKey());
    }

    public function testHasUriKey()
    {
        $uriKey = $this->metric->uriKey();
        $this->assertTrue($this->metric->hasUriKey($uriKey));
        $this->assertFalse($this->metric->hasUriKey('xxxxxxx'));
    }

    public function testAjaxRequest()
    {
        $request = $this->createMock(Request::class);

        $this->assertIsArray($this->metric->ajaxRequest($request));
    }


    // -------------------------------------------------------------------------
    // trait DisplayAsCard
    // -------------------------------------------------------------------------
    public function testDisplayAsCardAttributes()
    {
        $this->assertObjectHasAttribute('width', $this->metric);
        $this->assertObjectHasAttribute('bootstrapWidths', $this->metric);
    }

    public function testRender()
    {
        $route = $this->createMock(\Illuminate\Routing\Route::class);
        $route->expects($this->any())->method('getName')->willReturn('prefix.prefix');

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn($route);
        $request->expects($this->any())->method('query')->willReturn('');

        Route::group([
            'prefix' => 'prefix',
            'as' => 'prefix.ajaxCard',
            'namespace' => 'Test',
            'middleware' => 'auth'
        ], function () {
                Route::get('ajaxCard', 'Ingreso@index')->name('ajaxCard');
        });

        $this->assertInstanceOf(HtmlString::class, $this->metric->render($request));
    }

    public function testBootstrapCardWidth()
    {
        $this->assertEquals('col-span-4', $this->metric->bootstrapCardWidth());
    }

    public function testTitle()
    {
        $this->assertStringContainsString('Metric Test', $this->metric->title());
    }

    public function testWidth()
    {
        $this->assertEquals('col-span-6', $this->metric->width('1/2')->bootstrapCardWidth());
    }

    public function testCardId()
    {
        $this->assertIsString($this->metric->cardId());
    }

    public function testUrlRoute()
    {
        $route = $this->createMock(\Illuminate\Routing\Route::class);
        $route->expects($this->any())->method('getName')->willReturn('prefix.prefix');

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn($route);
        $request->expects($this->any())->method('query')->willReturn('');

        Route::group([
            'prefix' => 'prefix',
            'as' => 'prefix.ajaxCard',
            'namespace' => 'Test',
            'middleware' => 'auth'
        ], function () {
            Route::get('ajaxCard', 'Ingreso@index')->name('ajaxCard');
        });

        $this->assertEquals(config('app.url') . '/prefix/ajaxCard', $this->metric->urlRoute($request));
    }
}
