<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Auth::routes();
Route::get('/auth/{provider}', 'SocialAuthController@redirectToProvider');
Route::get('/auth/{provider}/callback', 'SocialAuthController@handleProviderCallback');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index');
