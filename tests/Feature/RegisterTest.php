<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_no_input()
    {
        $response = $this->postJson('/api/auth/signin');
        $response->assertStatus(422);
    }

    public function test_invalid_input()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->phoneNumber,
            'password' => $this->faker->password
        ];

        $response = $this->postJson('/api/auth/signin', $data);
        $response->assertStatus(422);
    }

    public function test_email_exist()
    {
        $password = $this->faker->password(10);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($password)
        ];
        $user = User::create($userData);

        $formData = [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->postJson('/api/auth/signin', $formData);
        $this->assertDatabaseHas('users', $userData);
        $response->assertStatus(409);
    }

    public function test_register_with_success()
    {
        $password = $this->faker->password(10);
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($password)
        ];
        $user = User::create($userData);

        $formData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($password)
        ];

        $response = $this->postJson('/api/auth/signin', $formData);
        $this->assertDatabaseMissing('users', $formData);
        $response->assertStatus(200);
    }
}
