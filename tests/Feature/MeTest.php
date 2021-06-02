<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_return_user_informations()
    {
        $credentials = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(10)
        ];
        $user = User::create($credentials);
        $this->actingAs($user);
        $loggedUser = User::where('email', Auth::user()->email)->get();
        $response = $this->postJson('/api/auth/me');
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email'])
            ->assertJson([
                'id' => $loggedUser[0]->id,
                'name' => $loggedUser[0]->name,
                'email' => $loggedUser[0]->email,
            ]);
    }
}
