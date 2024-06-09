<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'name', 'email', 'created_at', 'updated_at'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_login_api()
    {
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'name' => 'Test User',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_login_with_invalid_credentials_api()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'name' => 'Test User',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_logout_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_update_profile_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/profile' ,
    [
        'name' => 'updated',
        'email' => 'updated@update.com'
    ]);

        $this->assertDatabaseHas('users' , [
            'name' => 'updated',
            'email' => 'updated@update.com'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => 'updated',
                'email' => 'updated@update.com',
            ]);
    }


}
