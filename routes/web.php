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

Route::get('/', 'CandidateController@validateInformation')->name('takeTest');
Route::get('/t', 'CandidateController@preStartTest')->name('preStartTest');
Route::get('/t/s', 'CandidateController@postStarTest')->name('postStartTest');
Route::post('/t/e', 'CandidateController@postEndTest')->name('postEndTest');
Route::get('/t/d', 'CandidateController@testFinished')->name('testFinished');
Route::get('/t/i', 'CandidateController@testInvalid')->name('testInvalid');

Route::get('/t/v', 'CandidateController@validateDuration')->name('testValidateDuration');

Route::get('/login', 'AuthController@getLogin')->name('getLogin');
Route::post('/login', 'AuthController@postLogin')->name('postLogin');

Route::middleware(['guest', 'invite_request'])->group(function () {
    Route::get('/register', 'AuthController@getRegister')->name('getRegister');
    Route::post('/register', 'AuthController@postRegister')->name('postRegister');
});

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::middleware(['redirect_if_not_authenticated'])->group(function () {
    // Tests
    Route::get('/test/add', 'TestController@getCreate')->name('testGetCreate');
    Route::post('/test/add', 'TestController@postCreate')->name('testPostCreate');
    Route::get('/test/{id}/edit', 'TestController@getEdit')->name('testGetEdit');
    Route::post('/test/{id}/edit', 'TestController@postEdit')->name('testPostEdit');
    Route::post('/test/{id}/delete', 'TestController@delete')->name('testDelete');
    Route::get('/tests', 'TestController@all')->name('testAll');
    Route::get('/tests/candidates', 'TestController@taken')->name('testTaken');
    Route::get('/candidate/{candidate_id}/test/{test_id}/review', 'TestController@review')->name('testReview');
    Route::post('/candidate/review/submit', 'TestController@reviewSubmit')->name('reviewSubmit');
    Route::post('/candidate/review/update', 'TestController@reviewUpdate')->name('reviewUpdate');

    Route::get('/me', 'UserController@profile')->name('userProfile');
    Route::post('/me/password/change', 'UserController@changePassword')->name('changePassword');
    // Questions
    Route::get('/question/add', 'QuestionController@create')->name('questionCreate');
    Route::post('/question/add', 'QuestionController@add')->name('questionAdd');

    Route::get('/question/{id}/edit', 'QuestionController@edit')->name('questionEdit');
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
    Route::post('/chapter/test/associate', 'ChapterController@chapterTestAssociate')->name('chapterTestAssociate');

    // User invite issue
    Route::get('/invite/user', 'IssueInviteController@show')->name('getIssueInvite');
    Route::post('/invite/user', 'IssueInviteController@issue')->name('postIssueInvite');
    Route::get('/invites/users', 'IssueInviteController@getUserInvites')->name('getUserInvites');
    Route::post('/invites/revoke', 'IssueInviteController@revoke')->name('revokeInvite');

    // Candidate invite issue
    Route::get('/invite/candidate', 'CandidateInviteController@getCreateInvite')->name('candidateGetCreateInvite');
    Route::post('/invite/candidate', 'CandidateInviteController@postCreateInvite')->name('candidatePostCreateInvite');

    // Reviews
    Route::get('/invite/candidate', 'CandidateInviteController@getCreateInvite')->name('candidateGetCreateInvite');

    // Roles
    Route::get('/roles/users', 'RolesController@getUserByRoles')->name('getUserByRoles');
    Route::post('/roles/user/assign', 'RolesController@assignRole')->name('assignRole');
    Route::post('/roles/user/revoke', 'RolesController@revokeRole')->name('revokeRole');
    Route::get('/roles/permissions', 'RolesController@getPermissionByRoles')->name('getPermissionByRoles');


});
