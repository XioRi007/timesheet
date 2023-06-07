<?php


use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UnitPageTest;

class ProjectsTest extends UnitPageTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routePrefix = 'projects';
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id
        ]);
        $this->itemId = $project->id;
    }

    public function test_project_can_be_created(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $client = Client::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('projects.store'), [
                'name' => fake()->name,
                'client_id' => $client->id,
                'rate' => fake()->numberBetween(0, 999.99)
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('projects.index');
    }

    public function test_project_cannot_be_created_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $client = Client::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('projects.store'), [
                'name' => '',
                'client_id' => $client->id,
                'rate' => 9999
            ]);
        $response->assertInvalid(['rate', 'name']);
    }


    public function test_project_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');


        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('projects.update', $project->id), [
                'name' => fake()->name,
                'rate' => fake()->numberBetween(0, 999.99),
                'status' => fake()->boolean
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('projects.index');
    }

    public function test_project_cannot_be_updated_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');


        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('projects.update', $project->id), [
                'name' => '',
                'rate' => 99999,
            ]);
        $response->assertInvalid(['rate', 'name']);
    }

}
