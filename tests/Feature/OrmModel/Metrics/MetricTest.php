<?php

namespace Tests\Feature\OrmModel\Metrics;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Metrics\Metric;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MetricTest extends TestCase
{
    use RefreshDatabase;

    protected $metric;
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metric = new class() extends Metric {
            public function getCurrentRange(Request $request): array
            {
                return $this->currentRange($request);
            }

            public function getPreviousRange(Request $request): array
            {
                return $this->previousRange($request);
            }
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testCurrentRange()
    {
        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('MTD');
        $this->assertIsArray($this->metric->getCurrentRange($request));
        $this->assertEquals(now()->startOfMonth()->day, $this->metric->getCurrentRange($request)[0]->day);
        $this->assertEquals(now()->startOfMonth()->month, $this->metric->getCurrentRange($request)[0]->month);

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('QTD');
        $this->assertIsArray($this->metric->getCurrentRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('YTD');
        $this->assertIsArray($this->metric->getCurrentRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('CURR_MONTH');
        $this->assertIsArray($this->metric->getCurrentRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('LAST_MONTH');
        $this->assertIsArray($this->metric->getCurrentRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn(10);
        $this->assertIsArray($this->metric->getCurrentRange($request));
    }

    public function testPreviousRange()
    {
        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('MTD');
        $this->assertIsArray($this->metric->getPreviousRange($request));
        $this->assertEquals(now()->startOfMonth()->day, $this->metric->getPreviousRange($request)[0]->day);
        $this->assertEquals(now()->subMonth()->startOfMonth()->month, $this->metric->getPreviousRange($request)[0]->month);

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('QTD');
        $this->assertIsArray($this->metric->getPreviousRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('YTD');
        $this->assertIsArray($this->metric->getPreviousRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('CURR_MONTH');
        $this->assertIsArray($this->metric->getPreviousRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn('LAST_MONTH');
        $this->assertIsArray($this->metric->getPreviousRange($request));

        $request = $this->makeMock(Request::class, ['input']);
        $request->expects($this->any())->method('input')->willReturn(10);
        $this->assertIsArray($this->metric->getPreviousRange($request));
    }
    public function testContent()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertEquals(HtmlString::class, get_class($this->metric->content($request)));
    }

    public function testContentScript()
    {
        $request = $this->makeMock(Request::class, []);

        $this->assertEquals(HtmlString::class, get_class($this->metric->contentScript($request)));
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
        $request = $this->makeMock(Request::class, []);

        $this->assertIsArray($this->metric->ajaxRequest($request));
    }


    // -------------------------------------------------------------------------
    // trait DisplayAsCard
    // -------------------------------------------------------------------------
    public function testAttributes()
    {
        $this->assertObjectHasAttribute('width', $this->metric);
        $this->assertObjectHasAttribute('bootstrapWidths', $this->metric);
    }

    public function testRender()
    {
        $route = $this->makeMock(Request::class, ['getName']);
        $route->expects($this->any())->method('getName')->willReturn('prefix.prefix');

        $request = $this->makeMock(Request::class, ['route', 'query']);
        $request->expects($this->any())->method('route')->willReturn($route);
        $request->expects($this->any())->method('query')->willReturn('');

        Route::group(['prefix' => 'prefix', 'as' => 'prefix.ajaxCard', 'namespace' => 'Test', 'middleware' => 'auth'], function () {
            Route::get('ajaxCard', 'Ingreso@index')->name('ajaxCard');
        });

        $this->assertEquals(HtmlString::class, get_class($this->metric->render($request)));
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
        $route = $this->makeMock(Request::class, ['getName']);
        $route->expects($this->any())->method('getName')->willReturn('prefix.prefix');

        $request = $this->makeMock(Request::class, ['route', 'query']);
        $request->expects($this->any())->method('route')->willReturn($route);
        $request->expects($this->any())->method('query')->willReturn('');

        Route::group(['prefix' => 'prefix', 'as' => 'prefix.ajaxCard', 'namespace' => 'Test', 'middleware' => 'auth'], function () {
            Route::get('ajaxCard', 'Ingreso@index')->name('ajaxCard');
        });

        $this->assertEquals(config('app.url').'/prefix/ajaxCard', $this->metric->urlRoute($request));
    }
}
