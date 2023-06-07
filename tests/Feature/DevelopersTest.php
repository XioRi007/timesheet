<?php


use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\UnitPageTest;

class DevelopersTest extends UnitPageTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routePrefix = 'developers';
        $developer = Developer::factory()->create();
        $this->itemId = $developer->id;
    }

    public function test_developer_can_be_created(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->post(route('developers.store'), [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->safeEmail(),
                'password' => 'password',
                'rate' => fake()->numberBetween(0, 999)
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('developers.index');
    }

    public function test_developer_cannot_be_created_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this
            ->actingAs($user)
            ->post(route('developers.store'), [
                'first_name' => '',
                'last_name' => '',
                'email' => 'aa',
                'password' => 'p',
                'rate' => 99999
            ]);
        $response->assertInvalid(['first_name', 'last_name', 'email', 'password', 'rate']);
    }

    public function test_developer_can_be_updated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('developers.update', $developer->id), [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'rate' => fake()->numberBetween(0, 999),
                'status' => fake()->boolean
            ]);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('developers.index');
    }

    public function test_developer_cannot_be_updated_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $developer = Developer::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('developers.update', $developer->id), [
                'first_name' => '',
                'last_name' => '',
                'rate' => 9999
            ]);
        $response->assertInvalid(['first_name', 'last_name', 'rate']);
    }
}
