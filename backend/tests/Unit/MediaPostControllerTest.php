<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\MediaPost;
use Tests\TestCase;
use Auth;

class MediaPostControllerTest extends TestCase
{
    
    /**
     * @covers \App\Http\Controllers\API\MediaPostController::store_mediapost
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
     * @covers \App\Http\Controllers\API\MediaPostController::store_mediapost
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
     * @covers \App\Http\Controllers\API\MediaPostController::show_mediapost
     */
    public function testShowMediaPostSuccess()
    {
        $uuid = Auth::user()->uuid;
        
        $this->json('GET', 'api/showmediapost/'.$uuid,['Accept' => 'application/json'])
        ->assertStatus(200);
    }
    
}
