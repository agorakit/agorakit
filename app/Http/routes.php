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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'GroupController@index');
Route::get('home', 'GroupController@index');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


Route::get('groups', 'GroupController@index');
Route::get('groups/create', 'GroupController@create');
Route::get('groups/{id}', 'GroupController@show');
Route::post('groups', 'GroupController@store');



Route::resource('user', 'UserController');
Route::resource('group', 'GroupController');
Route::resource('groupuser', 'GroupUserController');
Route::resource('action', 'ActionController');
Route::resource('discussion', 'DiscussionController');
Route::resource('vote', 'VoteController');
Route::resource('file', 'FileController');
Route::resource('document', 'DocumentController');
