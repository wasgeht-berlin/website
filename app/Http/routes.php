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
Route::get('/calendar', ['uses' => 'AppController@calendar', 'as' => 'app.calendar']);
Route::get('/map', ['uses' => 'AppController@map', 'as' => 'app.map']);
Route::get('/contribute', ['uses' => 'AppController@contribute', 'as' => 'app.contribute']);
Route::get('/about', ['uses' => 'AppController@about', 'as' => 'app.about']);

Route::get('/data', ['uses' => 'AppController@data', 'as' => 'app.data']);
