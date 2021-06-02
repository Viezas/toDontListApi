<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_task_does_not_exist()
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


        $response = $this->get('/api/tasks/1');
        $this->assertDatabaseMissing('tasks', $newTask);
        $response->assertStatus(500);
    }
    

    public function test_return_task_successfully()
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
        $userTask = Task::where('id', '1')->where('user_id', Auth::id())->get();
        
        $response = $this->get('/api/tasks/1');
        $this->assertDatabaseHas('tasks', $newTask);
        $response->assertStatus(200);
    }
}
