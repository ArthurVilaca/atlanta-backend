<?php

use Illuminate\Http\Request;

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

Route::get('/', function() {
    return response()->json(['message' => 'Atlanta API', 'status' => 'Connected']);
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/user', 'UserController@index');
});

Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');
