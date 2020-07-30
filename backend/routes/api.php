<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MediaPostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


    Route::post('mediapost', 'API\MediaPostController@store_mediapost');
    Route::get('showmediapost/{id}', 'API\MediaPostController@show_mediapost');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post('/register', 'API\AuthController@register');
    Route::post('/login', 'API\AuthController@login');


    Route::middleware('auth:api')->get('/logout', 'API\AuthController@logout');
    Route::middleware('auth:api')->get('/user', 'API\AuthController@user');
    
