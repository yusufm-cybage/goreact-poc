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
 
	//UserAuth routes
    Route::post('/register', 'API\AuthController@register');
    Route::post('/login', 'API\AuthController@login');
    Route::middleware('auth:api')->get('/user', 'API\AuthController@user');
    Route::middleware('auth:api')->get('/logout', 'API\AuthController@logout');

    //mediaupload routes
    Route::middleware('auth:api')->post('/mediapost/search', 'API\MediaPostController@search');
    Route::middleware('auth:api')->post('/mediapost', 'API\MediaPostController@storeMediaPost');
    Route::middleware('auth:api')->get('/mediapost', 'API\MediaPostController@mediaAll');
    Route::middleware('auth:api')->get('/mediapost/user/{uuid}', 'API\MediaPostController@showMediaPost');
