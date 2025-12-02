<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong'
        ]);

        $response->assertStatus(401);
    }

    public function test_me_endpoint_requires_auth()
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_endpoint_returns_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken("token")->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $user->email]);
    }
}
