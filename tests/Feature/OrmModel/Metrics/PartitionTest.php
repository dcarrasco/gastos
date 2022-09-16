<?php

namespace Tests\Feature\OrmModel\Metrics;

use App\Models\Acl\App;
use App\Models\Acl\Modulo;
use App\Models\Acl\Usuario;
use App\OrmModel\src\Metrics\Partition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PartitionTest extends TestCase
{
    use RefreshDatabase;

    protected $partition;

    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Request::class);

        $this->partition = new class() extends Partition
        {
            public function calculate(Request $request): Collection
            {
                return collect([1 => 100, 2 => 50, 3 => 20]);
            }
        };
    }

    public function testCalculate()
    {
        $this->assertCount(3, $this->partition->calculate($this->request));

        $partition = new class() extends Partition
        {
        };
        $this->assertEmpty($partition->calculate($this->request));
    }

    public function testAggregators()
    {
        $usuarios = Usuario::factory(5)->create(['activo' => 1]);
        $usuarios = Usuario::factory(2)->create(['activo' => 0]);

        $this->assertEquals(
            ['1' => '5', '0' => '2'],
            $this->partition->count($this->request, \App\OrmModel\Acl\Usuario::class, 'activo')->all()
        );

        $this->assertEquals(
            ['1' => '5', '0' => '0'],
            $this->partition->sum($this->request, \App\OrmModel\Acl\Usuario::class, 'activo', 'activo')->all()
        );
    }

    public function testAggregatorsWithRelations()
    {
        $app1 = App::factory()->create();
        $app2 = App::factory()->create();

        $modulo1 = Modulo::factory(5)->create(['app_id' => $app1->id]);
        $modulo2 = Modulo::factory(2)->create(['app_id' => $app2->id]);

        $this->assertEquals(
            [$app1->descripcion => '5', $app2->descripcion => '2'],
            $this->partition
                ->count($this->request, \App\OrmModel\Acl\Modulo::class, 'acl_app.descripcion', 'app')
                ->all()
        );

        $this->assertEquals(
            [$app1->descripcion => $modulo1->sum('orden'), $app2->descripcion => $modulo2->sum('orden')],
            $this->partition
                ->sum($this->request, \App\OrmModel\Acl\Modulo::class, 'acl_app.descripcion', 'acl_modulo.orden', 'app')
                ->all()
        );
    }

    public function testContent()
    {
        $this->assertIsArray($this->partition->content($this->request)->toHtml()->getData());
        $this->assertArrayHasKey('cardId', $this->partition->content($this->request)->toHtml()->getData());
        $this->assertArrayHasKey('script', $this->partition->content($this->request)->toHtml()->getData());
    }

    public function testContentAjaxRequest()
    {
        $this->assertIsArray($this->partition->contentAjaxRequest($this->request));
    }

    public function testContentScript()
    {
        $this->assertIsString($this->partition->contentScript($this->request)->toHtml());
    }
}
