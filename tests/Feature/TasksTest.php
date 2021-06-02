<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TasksTest extends TestCase
{
    public function test_return_tasks_successfully()
    {
        $credentials = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(10)
        ];
        $user = User::create($credentials);
        $this->actingAs($user);

        $newTask = [
            'body' => $this->faker->text($maxNbChars = 10),
            'done' => false,
            'user_id' => $user->id
        ];

        $task = Task::create($newTask);
        $tasks = Auth::user()->tasks()->latest()->get();

        $response = $this->get('/api/tasks');
        $response->assertStatus(201);
    }
}
