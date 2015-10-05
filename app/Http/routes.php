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


/**
 * Authentification routes
 * TODO : add socila logins
 */
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);


/*


So we will basically have this scheme :

group/{id}
discussion/{id}
file/{id}
user/{id}
document/{id}
action/{id}


Then
group/discussions
group/files
group/users
group/documents
group/actions

-> I don't want slugs

-> I don't want group/{id}/discussion/{id}

Because a discussion could be moved or be part of several group or wathever.
And because urls this way are easy to construct.
Views need to be smart enough to build up context from an item id, but its the controller job to do that.
Imho



Each page (view) would need to know

- in which group we curently are (if any) and build a group navigation and related breadcrumb like : Home -> Groupname -> Discussions -> Discussion Title
- a list of groups of the current user and list it in a dropdown nav


As for actions, like creating a discussion, we need to provide a group id to each action.

It means something like :

discussion/create?group_id=5


*/


Route::get('group/{id}/discussions', 'DiscussionController@index');



Route::resource('user', 'UserController');
Route::resource('group', 'GroupController');
Route::resource('groupuser', 'GroupUserController');
Route::resource('action', 'ActionController');
Route::resource('discussion', 'DiscussionController');
Route::resource('vote', 'VoteController');
Route::resource('file', 'FileController');
Route::resource('document', 'DocumentController');
