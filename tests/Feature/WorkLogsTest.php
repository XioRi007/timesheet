<?php


use App\Models\Client;
use App\Models\Developer;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UnitPageTest;

class WorkLogsTest extends UnitPageTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routePrefix = 'worklogs';
        $client = Client::factory()->create();
        $project = Project::factory()->create([
            'client_id' => $client->id
        ]);
        $developer = Developer::factory()->create();
        $worklog = WorkLog::factory()->create([
            'project_id'=>$project->id,
            'developer_id'=>$developer->id,
            'hrs'=>1
        ]);
        $this->itemId = $worklog->id;
    }

    public function test_worklog_can_be_created(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $project = Project::first();
        $developer = Developer::first();

        $response = $this
            ->actingAs($user)
            ->post(route('worklogs.store'), [
                'date' => date('Y-m-d'),
                'project_id' => $project->id,
                'developer_id' => $developer->id,
                'rate' => fake()->numberBetween(0, 999),
                'hrs' => fake()->numberBetween(0, 6),
                'total' => fake()->numberBetween(0, 99999999)
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('worklogs.index');
    }

    public function test_worklog_cannot_be_created_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $project = Project::first();
        $developer = Developer::first();

        $response = $this
            ->actingAs($user)
            ->post(route('worklogs.store'), [
                'date' => date('Y-m-d'),
                'project_id' => $project->id,
                'developer_id' => $developer->id,
                'rate' => 9999,
                'hrs' => 9999,
                'total' => 999999999999
            ]);
        $response->assertInvalid(['rate', 'hrs', 'total']);
    }


    public function test_worklog_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $project = Project::first();
        $developer = Developer::first();
        $worklog = WorkLog::first();

        $response = $this
            ->actingAs($user)
            ->put(route('worklogs.update', $worklog->id), [
                'date' => date('Y-m-d'),
                'project_id' => $project->id,
                'developer_id' => $developer->id,
                'rate' => fake()->numberBetween(0, 999),
                'hrs' => fake()->numberBetween(0, 6),
                'total' => fake()->numberBetween(0, 99999999),
                'status' => fake()->boolean
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('worklogs.index');
    }


    public function test_worklog_cannot_be_updated_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $project = Project::first();
        $developer = Developer::first();
        $worklog = WorkLog::first();

        $response = $this
            ->actingAs($user)
            ->put(route('worklogs.update', $worklog->id), [
                'date' => date('Y-m-d'),
                'project_id' => $project->id,
                'developer_id' => $developer->id,
                'rate' => 9999,
                'hrs' => 9999,
                'total' => 999999999999
            ]);
        $response->assertInvalid(['rate', 'hrs', 'total']);
    }

    public function test_create_page_is_not_displayed_for_developer(): void
    {
        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($developer->user)
            ->get(route($this->routePrefix . '.create'));

        $response->assertStatus(200);
    }
}