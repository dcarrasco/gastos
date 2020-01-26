<?php

namespace Tests\Helpers;

use App\Helpers\CaptchaWord;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CaptchaWordTest extends TestCase
{
    public function testWordIsString()
    {
        $this->assertNotEmpty(CaptchaWord::word());
    }

    public function testWordLength()
    {
        $this->assertEquals(8, strlen(CaptchaWord::word()));
    }
}
