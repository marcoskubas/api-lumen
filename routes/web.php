<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function() use($router){

    $router->post('/users', 'UserController@store');

    $router->post('/login', 'UserController@login');

    Route::get('/verification-account/{token}', 'UserController@verificationAccount');

    $router->group(['middleware' => ['auth']], function() use($router){

        $router->post('/refresh-token', 'UserController@refreshToken');

    });

    $router->group(['middleware' => ['auth', 'token-expired']], function() use($router){

        $router->get('/clients', 'UserController@clients');
        $router->get('/user-auth', 'UserController@userAuth');

    });

});