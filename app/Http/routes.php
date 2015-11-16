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
Homepage
========

Basic homepage for all users, either logged in or not.
The idea is to provide a group listing (most active first) and a list of groups subscribed to by the current user.
*/
Route::get('/', 'GroupController@index');
Route::get('home', 'GroupController@index');


Route::get('register/confirm/{token}', 'Auth\AuthController@confirmEmail');


/*
General unread stuff, summary and dashboard
===========================================
*/
Route::get('unread', 'DashboardController@unreadDiscussions');
Route::get('agenda', 'DashboardController@agenda');
Route::get('agenda/json', 'DashboardController@agendaJson');


/*
Authentification routes
=======================
*/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
Group related routes
====================

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
Route::get('groups/create', 'GroupController@create');
Route::post('groups/create', 'GroupController@store');

// specific group homepage
Route::get('groups/{group}', 'GroupController@show');

// group edit
Route::get('groups/{group}/edit', 'GroupController@edit');
Route::post('groups/{group}/edit', 'GroupController@update');

// group history
Route::get('groups/{group}/history', 'GroupController@history');

// memberships & settings
Route::get('groups/{group}/join', 'MembershipController@joinForm');
Route::post('groups/{group}/join', 'MembershipController@join');

Route::get('groups/{group}/settings', 'MembershipController@settingsForm');
Route::post('groups/{group}/settings', 'MembershipController@settings');

Route::get('groups/{group}/leave', 'MembershipController@leaveForm');
Route::post('groups/{group}/leave', 'MembershipController@leave');


// invites
Route::get('groups/{group}/invite', 'InviteController@invite');
Route::post('groups/{group}/invite', 'InviteController@sendInvites');
Route::get('groups/{group}/invite/confirm/{token}', 'InviteController@inviteConfirm');
Route::post('groups/{group}/invite/confirm/{token}', 'InviteController@inviteRegister');



// Discussions
Route::get('groups/{group}/discussions', 'DiscussionController@index');
Route::get('groups/{group}/discussions/create', 'DiscussionController@create');
Route::post('groups/{group}/discussions/create', 'DiscussionController@store');

Route::get('groups/{group}/discussions/unread', 'DiscussionController@indexUnRead');

Route::get('groups/{group}/discussions/{discussion}', 'DiscussionController@show');
Route::get('groups/{group}/discussions/{discussion}/edit', 'DiscussionController@edit');
Route::post('groups/{group}/discussions/{discussion}', 'DiscussionController@update');

// group history
Route::get('groups/{group}/discussions/{discussion}/history', 'DiscussionController@history'); // TODO discussions history





// Notification email test
Route::get('groups/{group}/notify', 'NotificationController@notify');

// Comments
Route::post('groups/{group}/discussions/{discussion}/reply', 'CommentController@reply');


Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/up', 'VoteController@up');
Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/down', 'VoteController@down');
Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/cancel', 'VoteController@cancel');


// Actions
Route::get('groups/{group}/actions', 'ActionController@index');
Route::get('groups/{group}/actions/create', 'ActionController@create');
Route::post('groups/{group}/actions/create', 'ActionController@store');
Route::get('groups/{group}/actions/json', 'ActionController@indexJson');

Route::get('groups/{group}/actions/{action}', 'ActionController@show');
Route::get('groups/{group}/actions/{action}/edit', 'ActionController@edit');
Route::post('groups/{group}/actions/{action}', 'ActionController@update');


// Files
Route::get('groups/{group}/files', 'FileController@index');
Route::get('groups/{group}/files/gallery', 'FileController@gallery');
Route::get('groups/{group}/files/create', 'FileController@create');
Route::post('groups/{group}/files/create', 'FileController@store');
Route::get('groups/{group}/files/{file}', 'FileController@show');
Route::get('groups/{group}/files/{file}/thumbnail', 'FileController@thumbnail');
Route::get('groups/{group}/files/{file}/preview', 'FileController@preview');


// Users
Route::get('users/{id}', 'UserController@show');
Route::get('groups/{group}/users', 'UserController@index');
