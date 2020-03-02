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

Route::post('/login', 'UserController@login');
Route::get('/test', 'test@showToken');
Route::post('/register', 'UserController@register');
Route::get('/logout', 'UserController@login');

Route::post('/create-product', 'ProductController@store');
Route::get('/read-product', 'ProductController@index');
Route::post('/update-product/{id}', 'ProductController@update');
Route::post('/delete-product/{id}', 'ProductController@delete');

Route::post('/create-category', 'CategoryController@store');
Route::get('/read-category', 'CategoryController@index');