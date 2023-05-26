<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Developer;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkLog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $clients = Client::factory(60)->create();
        foreach ($clients as $client) {
            Project::factory(2)->create([
                'client_id' => $client->id
            ]);
        }

        $developers = Developer::factory(60)->create();
        foreach ($developers as $developer) {
            WorkLog::factory(3)->create([
                'developer_id' => $developer->id,
                'project_id' => rand(1, 120)
            ]);
        }
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com'
        ]);
    }
}
