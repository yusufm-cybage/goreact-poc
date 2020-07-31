<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }


    public function testSuccessfulRegistration()
    {   
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $this->json('POST', 'api/register',$userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                        "name",
                        "email",
                        "uuid",
                        "updated_at",
                        "created_at",
                        "id"
                    ],
                "access_token",
                "message"
            ]);;
    }

    public function testSuccessfulLogin()
    {
        $user = factory(User::class)->create([
            "name" => "guest",
            "email" => "guest@test.com",
            "password" => Hash::make("password"),
        ]);

        $loginData = ['email' => 'guest@test.com', 'password' => 'password'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "name",
                "uuid",
                "access_token",
                "token_type",
                "expires_at",
                "message"
            ]);;

        $this->assertAuthenticated();
    }

    public function testMissingInputInRegistration()
    {   
        $userData = [
            "name" => "John Doe",            
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $this->json('POST', 'api/register',$userData, ['Accept' => 'application/json',])
            ->assertStatus(422);
    }

    
    
}
