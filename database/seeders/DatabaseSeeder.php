<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Developer;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkLog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $developerRole = Role::create(['name' => 'developer']);

        $clients = Client::factory(60)->create();
        foreach ($clients as $client) {
            Project::factory(2)->create([
                'client_id' => $client->id
            ]);
        }
        foreach (range(1, 60) as $number) {
            $first_name = fake()->firstName;
            $last_name = fake()->lastName;
            $user = User::factory()->create([
                'name'=>$first_name . ' '. $last_name
            ]);
            $user->assignRole($developerRole);
            $developer = Developer::factory()->create([
                'user_id'=>$user->id,
                'first_name'=>$first_name,
                'last_name'=>$last_name,
            ]);
            WorkLog::factory(3)->create([
                'developer_id' => $developer->id,
                'project_id' => rand(1, 120)
            ]);
        }
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@email.com'
        ]);
        $admin->assignRole($adminRole);
    }
}
