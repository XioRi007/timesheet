<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UnitPageTest;

class ClientsTest extends UnitPageTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routePrefix = 'clients';
        $developer = Client::factory()->create();
        $this->itemId = $developer->id;
    }

    public function test_client_can_be_created(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->post(route('clients.store'), [
                'name' => fake()->name,
                'rate' => fake()->numberBetween(0, 999.99)
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('clients.index');
    }

    public function test_client_cannot_be_created_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->post(route('clients.store'), [
                'name' => '',
                'rate' => 99999
            ]);
        $response->assertInvalid(['name', 'rate']);
    }

    public function test_client_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $client = Client::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('clients.update', $client->id), [
                'name' => fake()->name,
                'rate' => fake()->numberBetween(0, 999.99),
                'status' => fake()->boolean
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('clients.index');
    }


    public function test_client_cannot_be_updated_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $client = Client::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('clients.update', $client->id), [
                'name' => '',
                'rate' => 99999,
                'status' => '',
            ]);
        $response->assertInvalid(['rate', 'name', 'status']);
    }
}
