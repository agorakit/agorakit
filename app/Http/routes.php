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


/**
 * Basic homepage for all users, either logged in or not.
 * The idea is to provide a group listing (most active first) and a list of groups subscribed to by the current user.
 */
Route::get('/', 'HomepageController@index');
Route::get('home', 'HomepageController@index');


/**
 * Authentification routes
 * TODO : add social logins
 */
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


/*

So we will basically have this scheme :

groups
groups/{group}
groups/{group}/discussions
groups/{group}/discussions/{id}
groups/{group}/discussions/{id}/create

groups/{group}/files/{id}
groups/{group}/users/{id}
groups/{group}/documents/{id}
groups/{group}/actions/{id}

-> I don't want slugs


Each page (view) would need to know

- in which group we curently are (if any) and build a group navigation and related breadcrumb like : Home -> Groupname -> Discussions -> Discussion Title
- a list of groups of the current user and list it in a dropdown nav

*/

/*
I will apply here the recomandtion "routes as documentation" from https://philsturgeon.uk/php/2013/07/23/beware-the-route-to-evil/
*/

// application homepage, lists all groups on the server
Route::get('groups', 'GroupController@index');

// specific group homepage
Route::get('groups/{group}', 'GroupController@show');

// Discussions
Route::get('groups/{group}/discussions', 'DiscussionController@index');
Route::get('groups/{group}/discussions/create', 'DiscussionController@create');
Route::post('groups/{group}/discussions/create', 'DiscussionController@store');
Route::get('groups/{group}/discussions/{discussion}', 'DiscussionController@show');
Route::get('groups/{group}/discussions/{discussion}/edit', 'DiscussionController@edit');
Route::post('groups/{group}/discussions/{discussion}', 'DiscussionController@update');

// Comments
// type can be curently 'discussion'
// so to reply to discussion with id 5, hit comment/discussion/5
Route::get('/comment/{type}/{id}', 'CommentController@create');
Route::post('/comment/{type}/{id}', 'CommentController@store');


// Files
Route::get('groups/{group}/files', 'FileController@index');
Route::get('groups/{group}/files/create', 'FileController@create');
Route::post('groups/{group}/files/create', 'FileController@store');
Route::get('groups/{group}/files/{file}', 'FileController@show');
Route::get('groups/{group}/files/{file}/thumbnail', 'FileController@thumbnail');


// Users
Route::get('users/{id}', 'UserController@show');


// no magic like this :
/*
Route::resource('user', 'UserController');
Route::resource('group', 'GroupController');
Route::resource('groupuser', 'GroupUserController');
Route::resource('action', 'ActionController');
Route::resource('vote', 'VoteController');
Route::resource('file', 'FileController');
Route::resource('document', 'DocumentController');
*/
