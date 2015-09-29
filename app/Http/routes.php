<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomepageController@index');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('group/{id}/discussions', 'DiscussionController@index');


Route::resource('user', 'UserController');
Route::resource('group', 'GroupController');
Route::resource('groupuser', 'GroupUserController');
Route::resource('action', 'ActionController');
Route::resource('discussion', 'DiscussionController');
Route::resource('vote', 'VoteController');
Route::resource('file', 'FileController');
Route::resource('document', 'DocumentController');
