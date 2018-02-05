<?php

use App\Acl\UserACL;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserACLTest extends TestCase
{

    public function testNew()
    {
        $this->assertInternalType('object', UserACL::new());
    }

    public function testToString()
    {
        $this->assertInternalType('string', (string) UserACL::new());
    }
}
