<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::updateOrCreate(['id' => 1], ['role_name' => 'Admin']);
        Role::updateOrCreate(['id' => 2], ['role_name' => 'Teacher']);
        Role::updateOrCreate(['id' => 3], ['role_name' => 'Student']);
    }

    public function test_all_get_routes()
    {
        $admin = User::where('role_id', 1)->first() ?? User::factory()->create(['role_id' => 1]);

        $routes = Route::getRoutes()->getRoutes();
        $skipPrefixes = ['_ignition', 'sanctum', 'api'];

        foreach ($routes as $route) {
            if (!in_array('GET', $route->methods())) continue;

            $uri = $route->uri();
            if (in_array($uri, ['migrate', 'up'])) continue;

            // Skip certain routes
            $skip = false;
            foreach ($skipPrefixes as $prefix) {
                if (str_starts_with($uri, $prefix)) $skip = true;
            }
            if ($skip || str_contains($uri, 'export')) continue;

            // Skip routes with parameters for now to avoid complexity, except if we can mock them easily
            if (str_contains($uri, '{')) continue;

            $response = $this->actingAs($admin)->get($uri);
            
            // Allow 200, 302, 301
            $this->assertTrue(in_array($response->status(), [200, 301, 302]), "Route $uri returned status " . $response->status());
        }
    }
}
