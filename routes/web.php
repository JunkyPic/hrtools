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
    // Invite issue
    Route::get('/invite', 'IssueInviteController@show')->name('getIssueInvite');
    Route::post('/invite', 'IssueInviteController@issue')->name('postIssueInvite');
    Route::get('/invites/users', 'IssueInviteController@getUserInvites')->name('getUserInvites');
    Route::post('/invites/revoke', 'IssueInviteController@revoke')->name('revokeInvite');

    Route::get('/me', 'UserController@profile')->name('userProfile');
    // Questions
    Route::get('/question/add', 'QuestionController@create')->name('questionCreate');
    Route::post('/question/add', 'QuestionController@add')->name('questionAdd');

    Route::get('/question/{id}', 'QuestionController@edit')->name('questionEdit');
    Route::post('/question/{id}/update', 'QuestionController@update')->name('questionUpdate');
    Route::post('/question/update/image', 'QuestionController@updateImages')->name('questionUpdateImages');

    Route::get('/questions', 'QuestionController@all')->name('questionsAll');
    Route::get('/questions/tagged', 'QuestionController@all')->name('questionsTaggedWith');
    Route::get('/questions/chapter', 'QuestionController@all')->name('questionsByChapters');

    Route::post('/question/{id}/delete', 'QuestionController@delete')->name('questionDelete');
    Route::post('/question/chapter/associate', 'QuestionController@questionChapterAssociate')->name('questionChapterAssociate');

    // Tags
    Route::get('/tags', 'TagController@all')->name('tagsAll');
    Route::get('/tag/add', 'TagController@create')->name('tagCreate');
    Route::post('/tag/add', 'TagController@add')->name('tagAdd');
    Route::get('/tag/{id}/edit', 'TagController@edit')->name('tagEdit');
    Route::post('/tag/{id}/edit', 'TagController@editPost')->name('tagEditPost');
    Route::post('/tag/{id}/delete', 'TagController@delete')->name('tagDelete');
    Route::post('/tag/manage', 'QuestionController@manageTag')->name('manageTag');

    // Chapters
    Route::get('/chapter/add', 'Chaptercontroller@getCreate')->name('chapterGetCreate');
    Route::post('/chapter/add', 'Chaptercontroller@postCreate')->name('chapterPostCreate');
    Route::get('/chapters', 'Chaptercontroller@all')->name('chapterAll');
    Route::get('/chapter/{id}/edit', 'Chaptercontroller@getEdit')->name('chapterGetEdit');
    Route::post('/chapter/{id}/edit', 'ChapterController@postEdit')->name('chapterPostEdit');
    Route::post('/chapter/{id}/delete', 'ChapterController@delete')->name('chapterDelete');

    // Tests
    Route::get('/test/add', 'TestController@getCreate')->name('testGetCreate');
    Route::get('/test/add', 'TestController@postCreate')->name('testPostCreate');
});