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
})->name('laravel');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');;

Route::get('/changepassword', 'UserController@getChangePassword')->name('changepassword')->middleware('auth');
Route::post('/changepassword', 'UserController@postChangePassword')->middleware('auth');

Route::get('/infomation', 'UserController@getInfomation')->name('infomation')->middleware('auth');
Route::get('/infomation/edit', 'UserController@editInfomation')->name('editinfomation')->middleware('auth');
Route::post('/infomation/edit', 'UserController@updateInfomation')->name('storeinfomation')->middleware('auth');

Route::get('/users', 'UserController@index')->middleware('auth');
Route::get('/users/create', 'UserController@create')->middleware('auth');
Route::post('/users/create', 'UserController@store')->middleware('auth');

Route::get('/users/edit/{id}', 'UserController@edit')->middleware('auth');
Route::post('/users/edit/{id}', 'UserController@update')->middleware('auth');

Route::get('/users/delete/{id}', 'UserController@destroy')->middleware('auth');
Route::post('/users/resetpassword/{id}', 'UserController@resetPassword')->middleware('auth');

Route::post('/users/resetmultiplepasswords', 'UserController@resetMultiplePasswords')->middleware('auth');

Route::get('/users/exportToExcel', 'UserController@exportToExcel')->middleware('auth');
Route::get('/users/autocomplete', 'UserController@autocomplete')->name('autocomplete')->middleware('auth');

Route::get('/departments', 'DepartmentController@index')->middleware('auth');
Route::get('/departments/create', 'DepartmentController@create')->middleware('auth');
Route::post('/departments/create', 'DepartmentController@store')->middleware('auth');

Route::get('/departments/edit/{id}', 'DepartmentController@edit');
Route::post('/departments/edit/{id}', 'DepartmentController@update');

Route::get('/departments/delete/{id}', 'DepartmentController@destroy')->middleware('auth');
