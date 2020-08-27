<?php

namespace Tests\Feature\OrmModel\OrmField;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use App\OrmModel\src\OrmField\BelongsTo;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BelongsToTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class('nombreCampo', 'app', \App\OrmModel\Acl\App::class) extends BelongsTo {
        };
    }

    protected function makeMock(string $class, array $methods)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    public function testAttributes()
    {
        $this->assertTrue($this->field->eagerLoadsRelation());
    }


    public function testGetFormattedValue()
    {
        $request = $this->makeMock(Request::class, []);
        $app = factory(\App\Acl\App::class)->create();
        $modulo = factory(\App\Acl\Modulo::class)->create(['app_id' => $app->id]);

        $this->assertEquals($app->app, $this->field->getFormattedValue($modulo, $request));
    }

    public function testModelAttribute()
    {
        $modulo = factory(\App\Acl\Modulo::class)->create();
        $resource = new \App\OrmModel\Acl\Modulo($modulo);

        $this->assertEquals('app_id', $this->field->getModelAttribute($resource));
    }

    public function testGetForm()
    {
        $request = $this->makeMock(Request::class, []);

        \Route::shouldReceive('currentRouteName')
            ->andReturn('url1.url2');

        \URL::shouldReceive('route')
            ->andReturn('url1');

        $apps = factory(\App\Acl\App::class, 3)->create();
        $modulo = factory(\App\Acl\Modulo::class)->create(['app_id' => $apps->first()->id]);
        $resource = new \App\OrmModel\Acl\Modulo($modulo);

        $this->assertIsObject($this->field->getForm($request, $resource));
        $this->assertEquals(HtmlString::class, get_class($this->field->getForm($request, $resource)));
        $this->assertStringContainsString($apps[0]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString($apps[1]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString($apps[2]->app, $this->field->getForm($request, $resource));
        $this->assertStringContainsString('extra="param"', $this->field->getForm($request, $resource, ['extra' => 'param']));
        $this->assertStringContainsString('uno:dos', $this->field->onChange('uno:dos')->getForm($request, $resource));
        $this->assertStringContainsString('url1', $this->field->onChange('uno:dos')->getForm($request, $resource));
    }
}
