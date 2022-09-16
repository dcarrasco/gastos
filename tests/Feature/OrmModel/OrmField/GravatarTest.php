<?php

namespace Tests\Feature\OrmModel\OrmField;

use App\OrmModel\src\OrmField\Gravatar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = new class('nombreCampo') extends Gravatar
        {
        };
    }

    public function testGetFormattedValue()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('route')->willReturn(
            (object) ['action' => ['as' => 'route.show']]
        );

        $model = $this->createMock(Model::class);
        $model->expects($this->any())->method('getAttribute')->willReturn(1);

        $this->assertStringContainsString(
            '<img src',
            $this->field->resolveValue($model, $request)->getFormattedValue()
        );
        $this->assertStringContainsString(
            '?size=240"',
            $this->field->resolveValue($model, $request)->getFormattedValue()
        );

        $request2 = $this->createMock(Request::class);
        $request2->expects($this->any())->method('route')->willReturn(
            (object) ['action' => ['as' => 'route.list']]
        );

        $this->assertStringContainsString(
            '?size=24"',
            $this->field->resolveValue($model, $request2)->getFormattedValue()
        );
    }
}
