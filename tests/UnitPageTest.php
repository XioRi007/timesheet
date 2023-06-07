<?php

namespace Tests;

use App\Models\Client;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnitPageTest extends TestCase
{
    protected string $routePrefix;
    protected string $itemId;

    public function test_page_is_displayed_for_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->get(route($this->routePrefix . '.index'));

        $response->assertStatus(200);
    }

    public function test_page_is_not_displayed_for_developer(): void
    {
        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($developer->user)
            ->get(route($this->routePrefix . '.index'));

        $response->assertStatus(403);
    }


    public function test_create_page_is_displayed_for_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->get(route($this->routePrefix . '.create'));

        $response->assertStatus(200);
    }

    public function test_create_page_is_not_displayed_for_developer(): void
    {
        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($developer->user)
            ->get(route($this->routePrefix . '.create'));

        $response->assertStatus(403);
    }
    public function test_edit_page_is_displayed_for_admin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->get(route($this->routePrefix . '.edit', $this->itemId));

        $response->assertStatus(200);
    }

    public function test_edit_page_is_not_displayed_for_developer(): void
    {
        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($developer->user)
            ->get(route($this->routePrefix . '.edit', $this->itemId));

        $response->assertStatus(403);
    }
    public function test_item_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->delete(route($this->routePrefix . '.destroy', $this->itemId));

        $response->assertStatus(200);
    }
}
