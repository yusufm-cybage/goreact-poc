<?php

namespace Tests\Unit;

use App\MediaPost;
use App\User;
use Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaPostControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * @covers \App\Http\Controllers\API\MediaPostController::storeMediaPost
     */
    public function testStoreMediaPostSuccess()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('goreact.jpg')->size(100);
        
        $mediaParams = [
            'file' => $file,
            'title' =>"Demo file",
            'tag' =>"Demo tag",
            'description' =>"Demo Description",
        ];
        $this->json('POST', 'api/mediapost', $mediaParams)
            ->assertStatus(200);
            
    }

    /**
     * @covers \App\Http\Controllers\API\MediaPostController::storeMediaPost
     */
    public function testStoreMediaPostFailure()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('goreact.jpg')->size(3000);
        $fileName  = time().'.'.$file->extension();
        $mediaParams = [
            'file' => $fileName,
            'title' =>"Demo file",
            'tag' =>"Demo tag",
            'description' =>"Demo Description",
        ];
        $this->json('POST', 'api/mediapost', $mediaParams)
            ->assertStatus(401);
            
    }

    /**
     * @covers \App\Http\Controllers\API\MediaPostController::showMediaPost
     */
    public function testShowMediaPostSuccess()
    {   
        $user = factory(User::class)->create();
        Auth::loginUsingId($user->id, true);
        $uuid = Auth::user()->uuid;         
        
        $this->json('GET', 'api/mediapost/user/'.$uuid,['Accept' => 'application/json'])
        ->assertStatus(200);
    }
    
}
