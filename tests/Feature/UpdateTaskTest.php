<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTaskTest extends TestCase
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

        $response = $this->putJson('/api/tasks/1');
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
            'body' => '',
            'done' => $this->faker->text($maxNbChars = 10)
        ];

        $response = $this->putJson('/api/tasks/1', $task);
        $response->assertStatus(422);
    }

    public function test_update_task_with_success()
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
            'done' => false,
            'user_id' => 1
        ]; 

        Task::create($task);

        $sendedTask = [
            'body' => $this->faker->text($maxNbChars = 10),
            'done' => true,
            'user_id' => 1
        ]; 

        Task::where('id', 1)->where('user_id', 1)->update(['body' => $sendedTask['body'], 'done' => $sendedTask['done']]);

        $response = $this->putJson('/api/tasks/1', $sendedTask);
        $this->assertDatabaseHas('tasks', $sendedTask);
        $response->assertStatus(200);
    }
}
