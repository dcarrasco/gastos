<?php

namespace Tests\Unit\OrmModel\Filters;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\OrmModel\src\Filters\PerPage;
use Illuminate\Database\Eloquent\Builder;

class PerPageTest extends TestCase
{

    protected $perPage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->perPage = new class () extends PerPage {
        };
    }

    public function testApply()
    {
        $request = $this->createMock(Request::class);
        $query = $this->createMock(Builder::class);

        $this->assertInstanceOf(Builder::class, $this->perPage->apply($request, $query, 'valor'));
    }

    public function testOptions()
    {
        $this->assertIsArray($this->perPage->options());
    }
}
