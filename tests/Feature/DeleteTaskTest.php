<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    public function test_delete_task_with_success()
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
            'done' => true,
            'user_id' => 1
        ];
        Task::create($task);

        Task::where('id', 1)->where('user_id', $task['user_id'])->delete();

        $response = $this->postJson('/api/tasks', $task);
        $this->assertDatabaseMissing('tasks', $task);
        $response->assertStatus(201);
    }
}