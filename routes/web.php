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
Route::get('/', 'HomeController@home')->name('home');

Route::get('/login', 'AuthController@getLogin')->name('getLogin');
Route::get('/register', 'AuthController@getRegister')->name('getRegister');

Route::post('/login', 'AuthController@postLogin')->name('postLogin');
Route::post('/register', 'AuthController@postRegister')->name('postRegister');

Route::get('/logout', 'AuthController@logout')->name('logout');


Route::middleware(['redirect_if_not_authenticated'])->group(function () {
    Route::get('/me', 'UserController@profile')->name('userProfile');
    Route::get('/admin', 'AdminController@adminFront')->name('adminFront');
});