<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api_request'])->group(function () {
    Route::namespace('Api')->group(function () {
        Route::get('/q/search', 'QuestionController@search')->name('searchQuestions');
        Route::get('/t/search', 'TagController@search')->name('searchTags');
    });
});