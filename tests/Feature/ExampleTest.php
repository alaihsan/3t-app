<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_guest_is_redirected_to_login_page(): void
    {
        $this->seed();
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
