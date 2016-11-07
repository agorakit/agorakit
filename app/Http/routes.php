<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {




  /*
  Authentification routes
  =======================
  */

  Route::get('confirm/{token}', 'Auth\AuthController@confirmEmail');
  Route::auth();


  /*
  Homepage
  ========

  Basic homepage for all users, either logged in or not.
  The idea is to provide a group listing (most active first) and a list of groups subscribed to by the current user.
  */
  Route::get('/', 'DashboardController@index');
  Route::get('home', 'DashboardController@index');
  Route::get('my', 'DashboardController@my');





  /*
  General unread stuff, summary and dashboard
  ===========================================
  */
  Route::get('discussions', 'DashboardController@discussions');
  Route::get('agenda', 'DashboardController@agenda');
  Route::get('agenda/json', 'DashboardController@agendaJson');
  Route::get('agenda/ical', 'IcalController@index');
  Route::get('users', 'DashboardController@users');
  Route::get('map', 'DashboardController@map');




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
  Route::get('groups', 'DashboardController@index');
  Route::get('groups/create', 'GroupController@create');
  Route::post('groups/create', 'GroupController@store');

  // Groups
  Route::get('groups/{group}', 'GroupController@show');
  Route::get('groups/{group}/cover', 'GroupController@cover');
  Route::get('groups/{group}/edit', 'GroupController@edit');
  Route::post('groups/{group}/edit', 'GroupController@update');
  Route::get('groups/{group}/history', 'GroupController@history');
  Route::get('groups/{group}/delete', 'GroupController@destroyConfirm');
  Route::delete('groups/{group}/delete', 'GroupController@destroy');


  // memberships & settings
  Route::get('groups/{group}/join', 'MembershipController@joinForm');
  Route::post('groups/{group}/join', 'MembershipController@join');

  Route::get('groups/{group}/settings', 'MembershipController@settingsForm');
  Route::post('groups/{group}/settings', 'MembershipController@settings');

  Route::get('groups/{group}/leave', 'MembershipController@leaveForm');
  Route::post('groups/{group}/leave', 'MembershipController@leave');

  // in the case of closed group, we show an howto join message
  Route::get('groups/{group}/howtojoin', 'MembershipController@howToJoin');

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

  // discussion history
  Route::get('groups/{group}/discussions/{discussion}/history', 'DiscussionController@history');


  // Notification email test
  // Route::get('groups/{group}/notify', 'NotificationController@notify');


  // Comments
  Route::post('groups/{group}/discussions/{discussion}/reply', 'CommentController@reply');

  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/edit', 'CommentController@edit');
  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/history', 'CommentController@history');
  Route::post('groups/{group}/discussions/{discussion}/comment/{comment}', 'CommentController@update');

  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/up', 'VoteController@up');
  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/down', 'VoteController@down');
  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/cancel', 'VoteController@cancel');

  Route::get('groups/{group}/discussions/{discussion}/comment/{comment}/delete', 'CommentController@destroyConfirm');
  Route::delete('groups/{group}/discussions/{discussion}/comment/{comment}/delete', 'CommentController@destroy');


  // Actions
  Route::get('groups/{group}/actions', 'ActionController@index');
  Route::get('groups/{group}/actions/create', 'ActionController@create');
  Route::post('groups/{group}/actions/create', 'ActionController@store');
  Route::get('groups/{group}/actions/json', 'ActionController@indexJson');

  Route::get('groups/{group}/actions/ical', 'IcalController@group');

  Route::get('groups/{group}/actions/{action}', 'ActionController@show');
  Route::get('groups/{group}/actions/{action}/edit', 'ActionController@edit');
  Route::post('groups/{group}/actions/{action}', 'ActionController@update');

  Route::get('groups/{group}/actions/{action}/history', 'ActionController@history');

  Route::get('groups/{group}/actions/{action}/delete', 'ActionController@destroyConfirm');
  Route::delete('groups/{group}/actions/{action}/delete', 'ActionController@destroy');


  // Files
  Route::get('groups/{group}/files', 'FileController@index');
  Route::get('groups/{group}/files/gallery', 'FileController@gallery');
  Route::get('groups/{group}/files/create', 'FileController@create');
  Route::post('groups/{group}/files/create', 'FileController@store');
  Route::get('groups/{group}/files/{file}', 'FileController@show');
  Route::get('groups/{group}/files/{file}/thumbnail', 'FileController@thumbnail');
  Route::get('groups/{group}/files/{file}/preview', 'FileController@preview');
  Route::get('groups/{group}/files/{file}/delete', 'FileController@destroyConfirm');
  Route::delete('groups/{group}/files/{file}/delete', 'FileController@destroy');

  Route::get('groups/{group}/files/{file}/edit', 'FileController@edit');
  Route::post('groups/{group}/files/{file}', 'FileController@update');



  // Users
  Route::get('users/{id}', 'UserController@show');

  Route::get('users/{id}/cover', 'UserController@cover');
  Route::get('users/{id}/avatar', 'UserController@avatar');

  Route::get('users/{id}/sendverification', 'UserController@sendVerificationAgain');

  Route::get('users/{id}/edit', 'UserController@edit');
  Route::post('users/{id}', 'UserController@update');

  Route::get('users/{id}/contact', 'UserController@contact');
  Route::post('users/{id}/contact', 'UserController@mail');

  Route::get('groups/{group}/users', 'UserController@index');



  Route::get('groups/{group}/map', 'MapController@map');


  // Tags
  Route::get('tags', 'TagController@index');
  Route::get('tags/{tag}', 'TagController@show');


  // Search
  Route::get('search', 'SearchController@index');



  /***************** ADMIN STUFF **************/
  /*
  Altough we want as little adimn so called "rights" or "power" some stuff must be handled by a small group of trusted people like:
  - group creation
  - homepage introduction text
  */

  Route::group(['middleware' => ['admin']], function () {

    Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('admin/settings', 'SettingsController@settings');
    Route::post('admin/settings', 'SettingsController@update');

    Route::resource('admin/user', 'AdminUserController');

  });

});
