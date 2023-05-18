<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Developer;
use App\Models\Project;
use App\Models\WorkLog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $clients = Client::factory(5)->create();
        foreach ($clients as $client) {
            Project::factory()->create([
                'client_id' => $client->id
            ]);
        }

        $developers = Developer::factory(10)->create();
        foreach ($developers as $developer) {
            WorkLog::factory()->create([
                'developer_id' => $developer->id,
                'project_id' => rand(1, 5)
            ]);
        }
    }
}
