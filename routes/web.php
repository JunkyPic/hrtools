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

// Invalid token route
Route::get('/i', 'IssueInviteController@invalidToken')->name('invalidToken');

Route::get('/', 'HomeController@home')->name('home');

Route::get('/login', 'AuthController@getLogin')->name('getLogin');
Route::get('/register', 'AuthController@getRegister')->name('getRegister')->middleware('invite_request');

Route::post('/login', 'AuthController@postLogin')->name('postLogin');
Route::post('/register', 'AuthController@postRegister')->name('postRegister');

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::middleware(['redirect_if_not_authenticated'])->group(function () {
    Route::get('/me', 'UserController@profile')->name('userProfile');
    Route::get('/admin', 'AdminController@adminFront')->name('adminFront');

    Route::get('/question/add', 'QuestionController@create')->name('questionCreate');
    Route::post('/question/add', 'QuestionController@add')->name('questionAdd');

    Route::get('/question/{id}', 'QuestionController@edit')->name('questionEdit');
    Route::post('/question/{id}/update', 'QuestionController@update')->name('questionUpdate');
    Route::post('/question/update/image', 'QuestionController@updateImages')->name('questionUpdateImages');

    Route::get('/questions', 'QuestionController@all')->name('questionsAll');
    Route::post('/question/{id}/delete', 'QuestionController@delete')->name('questionDelete');
    Route::get('/questions/tagged', 'QuestionController@questionsTaggedWith')->name('questionsTaggedWith');

    // Invite issue
    Route::get('/invite', 'IssueInviteController@show')->name('getIssueInvite');
    Route::post('/invite', 'IssueInviteController@issue')->name('postIssueInvite');

    // Tags
    Route::get('/tags', 'TagController@all')->name('tagsAll');
    Route::get('/tag/add', 'TagController@create')->name('tagCreate');
    Route::post('/tag/add', 'TagController@add')->name('tagAdd');
    Route::get('/tag/{id}/edit', 'TagController@edit')->name('tagEdit');
    Route::post('/tag/{id}/edit', 'TagController@editPost')->name('tagEditPost');
    Route::post('/tag/{id}/delete', 'TagController@delete')->name('tagDelete');
    Route::post('/tag/manageTag', 'QuestionController@manageTag')->name('manageTag');

});