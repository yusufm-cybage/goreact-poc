<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

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
                    'created_at',
                    'updated_at',
                ],
                "access_token"                
            ]);
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
                    'created_at',
                    'updated_at',
                ],
                "access_token"                
            ]);
    }
}
