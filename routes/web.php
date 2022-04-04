<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    Route::post('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('profile', 'AuthController@profile');

    // Todo Routes
    $router->group(['prefix' => 'todos'], function () use ($router) {
        $router->post('create', 'TodoController@store');
        $router->get('{id}', 'TodoController@show');
        $router->get('', 'TodoController@index');
        $router->put('{id}', 'TodoController@update');
        $router->delete('{id}', 'TodoController@destroy');
        // marsk as complete and incomplete todos routes
        $router->put('{id}/complete', 'TodoController@markAsComplete');
        $router->put('{id}/incomplete', 'TodoController@markAsIncomplete');
    });
    
    
});
