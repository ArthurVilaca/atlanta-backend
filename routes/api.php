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
    //Rotas de usuario
    Route::resource('user', 'UserController', ['except' => [
        'store'
    ]]);

    //Rotas de cliente
    Route::resource('client', 'ClientController');
    
    //Rotas de revendedor
    Route::resource('dealer', 'DealerController', ['except' => [
        'store'
    ]]);
});

Route::post('/dealer', 'DealerController@store');
Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

