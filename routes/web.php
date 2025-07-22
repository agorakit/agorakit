<?php

use Spatie\Honeypot\ProtectAgainstSpam;

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

/*

So we will basically have this scheme :

groups
groups/{group}
groups/{group}/discussions
groups/{group}/discussions/{id}
groups/{group}/discussions/{id}/create

groups/{group}/files/{id}
groups/{group}/calendarevents/{id}

etc.

users/{id}


-> I don't want slugs (except for users for now)


Each page (view) would need to know

- in which group we curently are (if any) and build a group navigation and related breadcrumb like : Home -> Groupname -> Discussions -> Discussion Title
- a list of groups of the current user and list it in a dropdown nav

*/

/*
I will apply here the recomandation "routes as documentation" from https://philsturgeon.uk/php/2013/07/23/beware-the-route-to-evil/
*/


Route::group(['middleware' => ['web']], function () {
    /*
    Authentification routes
    =======================
    */

    Route::get('confirm/{token}', 'Auth\RegisterController@confirmEmail');

    Route::middleware(ProtectAgainstSpam::class)->group(function () {
        Auth::routes();

        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@handleRegistrationForm');

        Route::get('register/password', 'Auth\RegisterController@showPasswordForm');
        Route::post('register/password', 'Auth\RegisterController@handlePasswordForm');
        Route::get('forgotpassword', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('passwordrequest');

        Route::get('login/email', 'Auth\LoginByEmailController@showLoginByEmailForm')->name('loginbyemail');
        Route::post('login/email', 'Auth\LoginByEmailController@sendLoginByEmail')->name('sendloginbyemail');
    });

    Route::get('autologin', 'Auth\AutoLoginController@login')->name('autologin');



    // PWA manifest.json
    Route::get('manifest.webmanifest', 'PwaController@index')->name('pwa.index');

    // Icons
    Route::get('icon/{size?}', 'IconController@index')->name('icon');


    /*
    Dashboard & common stuff
    ========================

    Basic homepage for all users, either logged in or not.
    The idea is to provide a group listing (most active first) and a list of groups subscribed to by the current user.
    */
    Route::get('/', 'DashboardController@index')->name('index');
    Route::get('presentation', 'DashboardController@presentation')->name('presentation');

    // Various content types index (discussion, files, users, etc...
    Route::get('discussions', 'GroupDiscussionController@index')->name('discussions');
    Route::get('users', 'UserController@index')->name('users');
    Route::get('files', 'FileController@index')->name('files');
    Route::get('map', 'MapController@index')->name('map');
    Route::get('map.geojson', 'MapController@geoJson')->name('map.geojson');

    Route::get('agenda', 'CalendarEventController@index')->name('agenda');
    Route::get('agenda/json', 'CalendarEventController@indexJson')->name('agenda.json');
    Route::get('agenda/ical', 'IcalController@index')->name('agenda.ical');
    Route::get('tags', 'TagController@index')->name('tags.index');
    Route::get('tags/{tag}', 'TagController@show')->name('tags.show');

    // Pages
    Route::get('pages/help', 'PageController@help')->name('pages.help');

    // Feeds (RSS)
    Route::get('discussions/feed', 'FeedController@discussions')->name('discussions.feed');
    Route::get('calendarevents/feed', 'FeedController@calendarevents')->name('calendarevents.feed');

    // Group handling
    Route::get('groups', 'GroupController@index')->name('groups.index');
    Route::get('groups/my', 'GroupController@indexOfMyGroups')->name('groups.index.my');
    Route::get('groups/create', 'GroupController@create')->name('groups.create');
    Route::post('groups/create', 'GroupController@store')->name('groups.store');
    Route::get('groups/import', 'GroupController@import')->name('groups.import');
    Route::post('groups/import', 'GroupController@import')->name('groups.import');

    // Group homepage and covers
    Route::get('groups/{group}', 'GroupController@show')->name('groups.show');
    Route::get('groups/{group}/cover/{size}', 'GroupCoverController@show')->name('groups.cover');

    // Invite system for groups
    Route::get('invites', 'InviteController@index')->name('invites.index');
    Route::get('invites/{membership}/accept', 'InviteController@accept')->name('invites.accept');
    Route::get('invites/{membership}/deny', 'InviteController@deny')->name('invites.deny');

    // Allows invited users to accept or deny invitations from a signed link sent to their mailbox
    Route::get('invite/{membership}/accept/signed', 'InviteController@acceptWithSignature')->name('invite.accept.signed');
    Route::get('invite/{membership}/deny/signed', 'InviteController@denyWithSignature')->name('invite.deny.signed');


    // General discussion create route
    Route::get('discussions/create', 'GroupDiscussionController@create')->name('discussions.create');
    Route::post('discussions/create', 'GroupDiscussionController@store')->name('discussions.store');

    // General event create & cover route
    Route::get('calendarevents/create', 'GroupCalendarEventController@create')->name('calendarevents.create');
    Route::post('calendarevents/create', 'GroupCalendarEventController@store')->name('calendarevents.store');

    Route::get('calendarevents/{action}/cover/{size}', 'CalendarEventCoverController@show')->name('calendarevents.cover');



    // Users
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/{user}/cover/{size}', 'UserCoverController@show')->name('users.cover');
    Route::get('users/{user}/sendverification', 'UserController@sendVerificationAgain')->name('users.sendverification');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::post('users/{user}', 'UserController@update')->name('users.update');
    Route::get('users/{user}/delete', 'UserController@destroy')->name('users.delete.confirm');
    Route::delete('users/{user}/delete', 'UserController@destroy')->name('users.delete');
    Route::get('users/{user}/contact', 'UserController@contactForm')->name('users.contactform');
    Route::post('users/{user}/contact', 'UserController@contact')->name('users.contact');

    // Ical feed per user
    Route::get('users/{user}/ical', 'UserIcalController@index')->name('users.ical');

    // Reactions on models
    Route::get('reactions/react/{model}/{id}/{reaction}', 'ReactionController@react')->name('reaction.react');
    Route::get('reactions/unreact/{model}/{id}', 'ReactionController@unReact')->name('reaction.unreact');

    // Notifications
    Route::get('notifications', 'NotificationController@index')->name('notifications');



    /*
    Groups
    ======
    */

    Route::group(['as' => 'groups', 'prefix' => 'groups/{group}'], function () {

        // Crud stuff
        Route::get('edit', 'GroupController@edit')->name('.edit');
        Route::post('edit', 'GroupController@update')->name('.update');
        Route::get('export', 'GroupController@export')->name('.export');
        Route::get('history', 'GroupController@history')->name('.history');
        Route::get('delete', 'GroupController@destroyConfirm')->name('.deleteconfirm');
        Route::delete('delete', 'GroupController@destroy')->name('.delete');

        // Modules (tabs enable disable on each group
        Route::get('custom', 'ModuleController@show')->name('.modules.show');
        Route::get('modules', 'ModuleController@edit')->name('.modules.edit');
        Route::post('modules', 'ModuleController@update')->name('.modules.update');

        // Mention at.js json routes
        Route::get('users/mention', 'MentionController@users')->name('.users.mention');
        Route::get('discussions/mention', 'MentionController@discussions')->name('.discussions.mention');
        Route::get('files/mention', 'MentionController@files')->name('.files.mention');


        // Group users : list
        Route::get('users', 'GroupMembershipController@index')->name('.users.index');
        // Join, apply, preferences and leave a group
        Route::get('join', 'GroupMembershipController@create')->name('.users.create');
        Route::post('join', 'GroupMembershipController@store')->name('.users.store');
        Route::get('preferences', 'GroupMembershipController@edit')->name('.users.edit');
        Route::post('preferences', 'GroupMembershipController@update')->name('.users.update');
        Route::get('leave', 'GroupMembershipController@destroyConfirm')->name('.users.deleteconfirm');
        Route::post('leave', 'GroupMembershipController@destroy')->name('.users.delete');

        // In the case of closed group, we show an how to join message (not in use currently)
        Route::get('howtojoin', 'GroupMembershipController@howToJoin')->name('.users.howtojoin');

        // Maps
        Route::get('map', 'GroupMapController@index')->name('.map');
        Route::get('map.geojson', 'GroupMapController@geoJson')->name('.map.geojson');


        // Discussions
        Route::get('discussions', 'GroupDiscussionController@index')->name('.discussions.index');
        Route::get('discussions/create', 'GroupDiscussionController@create')->name('.discussions.create');
        Route::post('discussions/create', 'GroupDiscussionController@store')->name('.discussions.store');

        Route::get('discussions/{discussion}', 'GroupDiscussionController@show')->name('.discussions.show');
        Route::get('discussions/{discussion}/edit', 'GroupDiscussionController@edit')->name('.discussions.edit');
        Route::post('discussions/{discussion}', 'GroupDiscussionController@update')->name('.discussions.update');

        Route::get('discussions/{discussion}/pin', 'GroupDiscussionController@pin')->name('.discussions.pin');
        Route::get('discussions/{discussion}/archive', 'GroupDiscussionController@archive')->name('.discussions.archive');

        Route::get('discussions/{discussion}/delete', 'GroupDiscussionController@destroyConfirm')->name('.discussions.deleteconfirm');
        Route::delete('discussions/{discussion}/delete', 'GroupDiscussionController@destroy')->name('.discussions.delete');

        // Discussion history
        Route::get('discussions/{discussion}/history', 'GroupDiscussionController@history')->name('.discussions.history');

        // Comments
        Route::post('discussions/{discussion}/reply', 'CommentController@store')->name('.discussions.reply');
        Route::get('discussions/{discussion}/comments/{comment}/edit', 'CommentController@edit')->name('.discussions.comments.edit');
        Route::post('discussions/{discussion}/comments/{comment}', 'CommentController@update')->name('.discussions.comments.update');
        Route::get('discussions/{discussion}/comments/{comment}/delete', 'CommentController@destroyConfirm')->name('.discussions.comments.deleteconfirm');
        Route::delete('discussions/{discussion}/comments/{comment}/delete', 'CommentController@destroy')->name('.discussions.comments.delete');
        Route::get('discussions/{discussion}/comments/{comment}/history', 'CommentController@history')->name('.discussions.comments.history');
        Route::get('discussions/{discussion}/live/{comment}', 'CommentController@live')->name('.discussions.live');

        // Events
        Route::get('calendarevents', 'GroupCalendarEventController@index')->name('.calendarevents.index');
        Route::get('calendarevents/create', 'GroupCalendarEventController@create')->name('.calendarevents.create');
        Route::post('calendarevents/create', 'GroupCalendarEventController@store')->name('.calendarevents.store');
        Route::get('calendarevents/json', 'GroupCalendarEventController@indexJson')->name('.calendarevents.index.json');
        Route::get('calendarevents/ical', 'GroupIcalController@index')->name('.calendarevents.index.ical');
        Route::get('calendarevents/{event}', 'GroupCalendarEventController@show')->name('.calendarevents.show');
        Route::get('calendarevents/{event}/edit', 'GroupCalendarEventController@edit')->name('.calendarevents.edit');
        Route::post('calendarevents/{event}', 'GroupCalendarEventController@update')->name('.calendarevents.update');
        Route::get('calendarevents/{event}/delete', 'GroupCalendarEventController@destroyConfirm')->name('.calendarevents.deleteconfirm');
        Route::delete('calendarevents/{event}/delete', 'GroupCalendarEventController@destroy')->name('.calendarevents.delete');
        Route::get('calendarevents/{event}/history', 'GroupCalendarEventController@history')->name('.calendarevents.history');

        // Event participation
        Route::get('calendarevents/{event}/participation/set/{status}', 'ParticipationController@set')->name('.calendarevents.participation.set');
        Route::get('calendarevents/{event}/participation', 'ParticipationController@edit')->name('.calendarevents.participation');
        Route::post('calendarevents/{event}/participation', 'ParticipationController@update')->name('.calendarevents.participation.update');

        // Files
        Route::get('files', 'GroupFileController@index')->name('.files.index');
        Route::get('files/create/{parent?}', 'GroupFileController@create')->name('.files.create');
        Route::post('files/create/{parent?}', 'GroupFileController@store')->name('.files.store');
        Route::get('files/createlink/{parent?}', 'GroupFileController@createLink')->name('.files.createlink');
        Route::post('files/createlink/{parent?}', 'GroupFileController@storeLink')->name('.files.storelink');
        Route::get('files/createfolder/{parent?}', 'GroupFileController@createFolder')->name('.files.createfolder');
        Route::post('files/createfolder/{parent?}', 'GroupFileController@storeFolder')->name('.files.storefolder');

        Route::get('files/{file}', 'GroupFileController@show')->name('.files.show');
        Route::get('files/{file}/edit', 'GroupFileController@edit')->name('.files.edit');
        Route::post('files/{file}', 'GroupFileController@update')->name('.files.update');
        Route::get('files/{file}/delete', 'GroupFileController@destroyConfirm')->name('.files.deleteconfirm');
        Route::delete('files/{file}/delete', 'GroupFileController@destroy')->name('.files.delete');

        Route::get('files/{file}/pin', 'GroupFileController@pin')->name('.files.pin');
        Route::get('files/{file}/archive', 'GroupFileController@archive')->name('.files.archive');

        Route::get('files/{file}/download', 'FileDownloadController@download')->name('.files.download');
        Route::get('files/{file}/thumbnail', 'FileDownloadController@thumbnail')->name('.files.thumbnail');
        Route::get('files/{file}/preview', 'FileDownloadController@preview')->name('.files.preview');
        Route::get('files/{file}/icon', 'FileDownloadController@icon')->name('.files.icon');


        /*
        Group admin
        ===========
        */

        // Mass invite
        Route::get('membership/create', 'GroupMassMembershipController@create')->name('.membership.create');
        Route::post('membership/store', 'GroupMassMembershipController@store')->name('.membership.store');

        // Edit existing memberships
        Route::get('membership/{membership}', 'GroupMembershipAdminController@edit')->name('.membership.edit');
        Route::post('membership/{membership}', 'GroupMembershipAdminController@update')->name('.membership.update');

        // Stats
        Route::get('insights', 'GroupInsightsController@index')->name('.insights');

        // Invites
        Route::get('invite', 'InviteController@invite')->name('.invite.form');
        Route::post('invite', 'InviteController@sendInvites')->name('.invite');

        // Stats
        Route::get('insights', 'GroupInsightsController@index')->name('.insights');

        // Allowed Tags
        Route::get('tags', 'GroupTagController@edit')->name('.tags.edit');
        Route::post('tags', 'GroupTagController@update')->name('.tags.update');

        // Permissions
        Route::get('permissions', 'GroupPermissionController@index')->name('.permissions.index');
        Route::post('permissions', 'GroupPermissionController@update')->name('.permissions.update');
    });

    // Search
    Route::get('search', 'SearchController@index');



    /*
    Server administration
    =====================

    Altough we want as little admin so called "rights" or "power" some stuff must be handled by a small group of trusted people like:
    - group creation (that is even questionable, and not yet the case - we want self service)
    - homepage introduction text
    - various global application settings
    */

    Route::group(['middleware' => ['admin']], function () {
        // logs
        Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('admin.logs');
        // settings
        Route::get('admin/settings', 'Admin\SettingsController@index')->name('admin.settings');
        Route::post('admin/settings', 'Admin\SettingsController@update')->name('admin.settings.store');
        // users
        Route::resource('admin/user', 'Admin\UserController')->name('*', 'admin.users');
        // stats
        Route::get('admin/insights', 'Admin\InsightsController@index')->name('admin.insights');
        // undelete content
        Route::get('admin/undo', 'UndoController@index')->name('admin.undo')->name('admin.undo');
        Route::get('admin/{type}/{id}/restore', 'UndoController@restore')->name('admin.restore');
        // list of group admins
        Route::get('admin/groupadmins', 'Admin\GroupAdminsController@index')->name('admin.adminusers');
        // list of groups
        Route::get('admin/group', 'Admin\GroupController@index')->name('admin.groups');
    });
});
