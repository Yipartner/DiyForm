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



Route::get('/form/url/{url}','FormController@getFormByUrl');
Route::post('/form/update{formId}','FormController@updateForm');
Route::post('/form/add','FormController@createForm');
Route::get('/form/delete/true/{formId}','FormController@trueDeleteForm');
Route::get('/form/delete/soft/{formId}','FormController@softDeleteForm');
Route::post('/form/change/{formId}','FormController@changeStatus');