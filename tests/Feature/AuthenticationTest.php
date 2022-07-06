<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_get_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
