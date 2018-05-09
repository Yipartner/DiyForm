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
    return view('welcome');
});

Route::get('/data/{formId}', 'DataController@getData');
Route::post('/data/{formId}', 'DataController@postData');
Route::get('/data/export/{formId}', 'DataController@exportData');