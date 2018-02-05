<?php

use App\Acl\App;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', App::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) App::new());
    }
}
