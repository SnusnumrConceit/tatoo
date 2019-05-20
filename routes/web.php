<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin');
});

//Route::get('/home', function () {
//    return view('home');
//});

Route::group([
    'prefix' => 'users'
], function () {
    Route::get('/', 'UserController@store');
    Route::get('/search', 'UserController@search');
    Route::get('/edit/{id}', 'UserController@edit')
        ->where('id', '[0-9]+');
    Route::get('/info/{id?}', 'UserController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'UserController@create');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'UserController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/export', 'UserController@export');
});

Route::group(['prefix' => 'masters'], function () {
    Route::get('/', 'EmployeeController@publish');
    Route::get('/{id}/tatoos', 'EmployeeController@getTatoos')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'employees'
], function () {
    Route::get('/', 'EmployeeController@store');
    Route::get('/search', 'EmployeeController@search');
    Route::get('/edit/{id}', 'EmployeeController@edit')
        ->where('id', '[0-9]+');
    Route::get('/info/{id}', 'EmployeeController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'EmployeeController@create');
    Route::post('/update/{id}', 'EmployeeController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'EmployeeController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/extends', 'EmployeeController@extends');
    Route::get('/export', 'EmployeeController@export');
});

Route::group([
    'prefix' => 'orders'
], function () {
    Route::get('/', 'OrderController@store');
    Route::get('/search', 'OrderController@search');
    Route::get('/edit/{id}', 'OrderController@edit')
        ->where('id', '[0-9]+');
    Route::get('/info/{id}', 'OrderController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'OrderController@create');
    Route::post('/update/{id}', 'OrderController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'OrderController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/extends', 'OrderController@extends');
    Route::get('/export', 'OrderController@export');
    Route::post('/publish', 'OrderController@publish');
});

Route::group([
    'prefix' => 'tatoos'
], function () {
    Route::get('/', 'TatooController@store');
    Route::get('/search', 'TatooController@search');
    Route::get('/edit/{id}', 'TatooController@edit')
        ->where('id', '[0-9]+');
    Route::get('/info/{id}', 'TatooController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'TatooController@create');
    Route::post('/update/{id}', 'TatooController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'TatooController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/export', 'TatooController@export');
    Route::get('/{id}/masters', 'TatooController@getTatooMasters')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'appointments'
], function () {
    Route::get('/', 'AppointmentController@store');
    Route::get('/search', 'AppointmentController@search');
    Route::get('/edit/{id}', 'AppointmentController@edit')
        ->where('id', '[0-9]+');
    Route::get('/info/{id}', 'AppointmentController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'AppointmentController@create');
    Route::post('/update/{id}', 'AppointmentController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'AppointmentController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/export', 'AppointmentController@export');
});

Route::group([
    'prefix' => 'image'
], function () {
   Route::post('/upload', 'ImageController@upload');
   Route::post('/remove', 'ImageController@remove');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/registration', 'RegisterController@registry');
    Route::post('/logout', 'LoginController@logout');
});

//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'audits'], function () {
    Route::get('/', 'AuditController@store');
});

Route::group([ 'prefix' => 'roles'], function () {
    Route::get('/', 'UserController@getRoles');
});