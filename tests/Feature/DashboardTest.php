<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_is_displayed_for_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
    }


    public function test_dashboard_page_is_not_displayed_for_developer(): void
    {
        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($developer->user)
            ->get('/');

        $response->assertStatus(403);
    }

}
