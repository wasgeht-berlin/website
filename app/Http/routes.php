<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['uses' => 'AppController@index', 'as' => 'app.index']);
//Route::get('/calendar', ['uses' => 'AppController@calendar', 'as' => 'app.calendar']);
//Route::get('/map', ['uses' => 'AppController@map', 'as' => 'app.map']);
Route::get('/contribute', ['uses' => 'AppController@contribute', 'as' => 'app.contribute']);
Route::get('/about', ['uses' => 'AppController@about', 'as' => 'app.about']);

Route::get('/api', ['uses' => 'AppController@api', 'as' => 'app.api']);

Route::group(['prefix' => '/api/v1'], function () {
    Route::pattern('id', '\d+');

    Route::post('/provider_update', [
        'uses' => 'AppController@update',
        'middleware' => ['hooks.github']
    ]);

    Route::get('/event', ['uses' => 'API\EventController@index']);
    Route::get('/event/{id}', ['uses' => 'API\EventController@get']);
    Route::get('/event/search', ['uses' => 'API\EventController@search']);
    Route::post('/event/search', ['uses' => 'API\EventController@search']);

    Route::get('/location', ['uses' => 'API\LocationController@index']);
    Route::get('/location/{id}', ['uses' => 'API\LocationController@get']);
});

Route::get('/robots.txt', ['uses' => 'AppController@robots']);
