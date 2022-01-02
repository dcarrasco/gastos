<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use App\Models\Acl\App;
use App\Models\Acl\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;
use App\OrmModel\src\OrmField\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BelongsToTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag());

        $this->field = new class ('nombreCampo', 'app', \App\OrmModel\Acl\App::class) extends BelongsTo {
        };
    }

    public function testAttributes()
    {
        $this->assertTrue($this->field->eagerLoadsRelation());
    }

    public function testGetFormattedValue()
    {
        $request = $this->createMock(Request::class);
        $app = App::factory()->create();
        $modulo = Modulo::factory()->create(['app_id' => $app->id]);

        $this->assertEquals(
            new HtmlString($app->app),
            $this->field->resolveValue($modulo, $request)->getFormattedValue()
        );
    }

    public function testModelAttribute()
    {
        $modulo = Modulo::factory()->create();
        $resource = new \App\OrmModel\Acl\Modulo($modulo);

        $this->assertEquals('app_id', $this->field->getModelAttribute($resource));
    }

    public function testGetForm()
    {
        $request = $this->createMock(Request::class);
        $route = $this->createMock(\Illuminate\Routing\Route::class);

        $request->expects($this->any())->method('route')->willReturn($route);
        $route->expects($this->any())->method('getName')->willReturn('name.action');
        $request->expects($this->any())->method('has')->willReturn(false);

        \URL::shouldReceive('route')
            ->andReturn('url1');

        $apps = App::factory(3)->create();
        $modulo = Modulo::factory()->create(['app_id' => $apps->first()->id]);
        $resource = new \App\OrmModel\Acl\Modulo($modulo);

        $this->assertIsObject($this->field->getForm($request, $resource));
        $this->assertInstanceOf(HtmlString::class, $this->field->getForm($request, $resource));
        $this->assertStringContainsString($apps[0]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString($apps[1]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString($apps[2]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString('uno:dos', $this->field->onChange('uno:dos')->getForm($request, $resource));
        $this->assertStringContainsString('url1', $this->field->onChange('uno:dos')->getForm($request, $resource));
    }
}
