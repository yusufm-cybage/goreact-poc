<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserAuthTest extends TestCase
{
    use DatabaseMigrations;

    public function testSuccessfulRegistration()
    {   
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $this->json('POST', 'api/register',$userData, ['Accept' => 'application/json',])
            ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'isAdmin',
                    'created_at',
                    'updated_at',
                ],
                "access_token"                
            ]);
    }

    public function testSuccessfulLogin()
    {
         
        $loginData = ['email' => 'guest@test.com', 'password' => 'password'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
               "user" => [
                   'id',
                   'name',
                   'email',
                   'isAdmin',
                   'email_verified_at',
                   'created_at',
                   'updated_at',
               ],
                "access_token",
                "message"
            ]);

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
            ->assertStatus(422)
            ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'isAdmin',
                    'created_at',
                    'updated_at',
                ],
                "access_token"                
            ]);
    }

    
}
