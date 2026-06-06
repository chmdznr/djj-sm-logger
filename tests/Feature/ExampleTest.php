<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_root_to_login(): void
    {
        // The root route in routes/web.php redirects to /login for guests.
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_login_page_is_reachable(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_health_endpoint_is_reachable(): void
    {
        $this->get('/up')->assertStatus(200);
    }
}
