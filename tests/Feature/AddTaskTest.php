<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddTaskTest extends TestCase
{
    public function test_no_input()
    {
        $credentials = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(10)
        ];
        $user = User::create($credentials);
        $this->actingAs($user);

        $response = $this->postJson('/api/tasks');
        $response->assertStatus(422);
    }

    public function test_invalid_input()
    {
        $credentials = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(10)
        ];
        $user = User::create($credentials);
        $this->actingAs($user);

        $task = [
            'body' => ''
        ];

        $response = $this->postJson('/api/tasks', $task);
        $response->assertStatus(422);
    }

    public function test_add_task_with_success()
    {
        $credentials = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(10)
        ];
        $user = User::create($credentials);
        $this->actingAs($user);

        $task = [
            'body' => $this->faker->text($maxNbChars = 10),
            'user_id' => 1
        ]; 

        $response = $this->postJson('/api/tasks', $task);
        $this->assertDatabaseHas('tasks', $task);
        $response->assertStatus(201);
    }
}
