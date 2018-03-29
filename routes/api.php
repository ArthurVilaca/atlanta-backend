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

Route::post('/dealer', 'DealerController@store');
Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

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

    //Rotas para components
    Route::resource('component', 'ComponentController');
    //Rotas páginas
    Route::resource('page', 'PageController');

    //Rota para trazer o componente de uma única página
    Route::get('page/{page}/components', 'PageController@componentsPage');
    Route::post('page/{page}/components', 'PageController@storeComponentPage');
    Route::put('page/{page}/components/{component}', 'PageController@updateComponentPage');
    
    //Rotas para buscar páginas de um client
    Route::get('page/client/{client_id}', 'PageController@pageClients');
    Route::post('page/client/{client_id}', 'PageController@newPageClients');

    //Rotas para contas a pagar
    Route::resource('billspay', 'BillspayController');

    //Rotas para contas a receber
    Route::resource('billsreceive', 'BillsreceiveController');
    
    //Rotas de midia
    // Route::resource('client', 'MidiaController');
});

Route::resource('midia', 'MidiaController');
