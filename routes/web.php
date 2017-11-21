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

    Route::get('confirm/{token}', 'Auth\RegisterController@confirmEmail');

    Auth::routes();

    // OAuth Routes
    Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

    // Autologin
    Route::get('autologin/{token}', ['as' => 'autologin', 'uses' => '\Watson\Autologin\AutologinController@autologin']);

    /*
    Homepage
    ========

    Basic homepage for all users, either logged in or not.
    The idea is to provide a group listing (most active first) and a list of groups subscribed to by the current user.
    */
    Route::get('/', 'DashboardController@index')->name('index');
    Route::get('home', 'DashboardController@index')->name('index');
    Route::get('presentation', 'DashboardController@presentation');
    Route::get('discussions', 'DashboardController@discussions')->name('discussions');
    Route::get('users', 'DashboardController@users')->name('users');
    Route::get('files', 'DashboardController@files')->name('files');
    Route::get('map', 'DashboardController@map')->name('map');
    Route::get('activities', 'DashboardController@activities')->name('activities');

    Route::get('agenda', 'DashboardController@agenda')->name('agenda');
    Route::get('agenda/json', 'DashboardController@agendaJson')->name('agenda.json');
    Route::get('agenda/ical', 'IcalController@index')->name('agenda.ical');

    /*
    Feeds (RSS ftw!)
    ===========================================
    */
    Route::get('discussions/feed', 'FeedController@discussions')->name('discussions.feed');
    Route::get('actions/feed', 'FeedController@actions')->name('actions.feed');

    /*
    Group related routes
    ====================

    So we will basically have this scheme :

    groups
    groups/{group}
    discussions
    discussions/{id}
    discussions/{id}/create

    files/{id}
    users/{id}
    documents/{id}
    actions/{id}

    -> I don't want slugs


    Each page (view) would need to know

    - in which group we curently are (if any) and build a group navigation and related breadcrumb like : Home -> Groupname -> Discussions -> Discussion Title
    - a list of groups of the current user and list it in a dropdown nav

    */

    /*
    I will apply here the recomandtion "routes as documentation" from https://philsturgeon.uk/php/2013/07/23/beware-the-route-to-evil/
    */



    // application homepage, lists all groups on the server
    Route::get('groups', 'DashboardController@groups')->name('groups.index');
    Route::get('groups/create', 'GroupController@create')->name('groups.create');
    Route::post('groups/create', 'GroupController@store')->name('groups.store');

    // Groups : what everyone can see
    Route::get('groups/{group}', 'GroupController@show')->name('groups.show');
    Route::get('groups/{group}/cover', 'GroupThumbnailController@cover')->name('groups.cover');
    Route::get('groups/{group}/cover/small', 'GroupThumbnailController@avatar')->name('groups.cover.small');
    Route::get('groups/{group}/cover/carousel', 'GroupThumbnailController@carousel')->name('groups.cover.carousel');

    // General discussion create route
    Route::get('discussions/create', 'DiscussionController@create')->name('discussions.create');
    Route::post('discussions/create', 'DiscussionController@store')->name('discussions.store');


    // Groups : only members (or everyone if a group is public)
    Route::group(['middleware' => 'public', 'as' => 'groups', 'prefix' => 'groups/{group}'], function () {

        // Crud stuff
        Route::get('edit', 'GroupController@edit')->name('.edit');
        Route::post('edit', 'GroupController@update')->name('.update');
        Route::get('history', 'GroupController@history')->name('.history');
        Route::get('delete', 'GroupController@destroyConfirm')->name('.deleteconfirm');
        Route::delete('delete', 'GroupController@destroy')->name('.delete');

        // Mention at.js json routes
        Route::get('users/mention', 'MentionController@users')->name('.users.mention');
        Route::get('discussions/mention', 'MentionController@discussions')->name('.discussions.mention');
        Route::get('files/mention', 'MentionController@files')->name('.files.mention');

        // memberships & preferences
        Route::get('join', 'MembershipController@create')->name('.membership.create');
        Route::post('join', 'MembershipController@store')->name('.membership.store');
        Route::get('preferences', 'MembershipController@edit')->name('.membership.edit');
        Route::post('preferences', 'MembershipController@update')->name('.membership.update');
        Route::get('leave', 'MembershipController@destroyConfirm')->name('.membership.deleteconfirm');
        Route::post('leave', 'MembershipController@destroy')->name('.membership.delete');


        // Stats
        Route::get('insights', 'InsightsController@forGroup')->name('.insights');

        // membership admins
        Route::get('users/add', 'Admin\MembershipController@addUserForm')->name('.users.create');
        Route::post('users/add', 'Admin\MembershipController@addUser')->name('.users.store');
        Route::get('users/{user}/admin', 'Admin\MembershipController@editUserForm')->name('.users.edit');
        Route::get('users/delete/{user}', 'Admin\MembershipController@removeUser')->name('.users.delete');
        Route::get('users/{user}/admin/add', 'Admin\MembershipController@addAdminUser')->name('.users.addadmin');
        Route::get('users/{user}/admin/delete', 'Admin\MembershipController@removeAdminUser')->name('.users.removeadmin');

        // in the case of closed group, we show an howto join message
        Route::get('howtojoin', 'MembershipController@howToJoin')->name('.howtojoin');

        // invites
        Route::get('invite', 'InviteController@invite')->name('.invite.form');
        Route::post('invite', 'InviteController@sendInvites')->name('.invite');
        Route::get('invite/confirm/{token}', 'InviteController@inviteConfirm')->name('.invite.confirm');
        Route::post('invite/confirm/{token}', 'InviteController@inviteRegister')->name('.invite.register');

        // Discussions
        Route::get('discussions', 'DiscussionController@index')->name('.discussions.index');
        Route::get('discussions/create', 'DiscussionController@create')->name('.discussions.create');
        Route::post('discussions/create', 'DiscussionController@store')->name('.discussions.store');

        Route::get('discussions/{discussion}', 'DiscussionController@show')->name('.discussions.show');
        Route::get('discussions/{discussion}/edit', 'DiscussionController@edit')->name('.discussions.edit');
        Route::post('discussions/{discussion}', 'DiscussionController@update')->name('.discussions.update');

        Route::get('discussions/{discussion}/delete', 'DiscussionController@destroyConfirm')->name('.discussions.deleteconfirm');
        Route::delete('discussions/{discussion}/delete', 'DiscussionController@destroy')->name('.discussions.delete');

        // discussion history
        Route::get('discussions/{discussion}/history', 'DiscussionController@history')->name('.discussions.history');

        // Notification email test
        // Route::get('notify', 'NotificationController@notify');

        // Comments
        Route::post('discussions/{discussion}/reply', 'CommentController@reply')->name('.discussions.reply');
        Route::get('discussions/{discussion}/comments/{comment}/edit', 'CommentController@edit')->name('.discussions.comments.edit');
        Route::post('discussions/{discussion}/comments/{comment}', 'CommentController@update')->name('.discussions.comments.update');
        Route::get('discussions/{discussion}/comments/{comment}/delete', 'CommentController@destroyConfirm')->name('.discussions.comments.deleteconfirm');
        Route::delete('discussions/{discussion}/comments/{comment}/delete', 'CommentController@destroy')->name('.discussions.comments.delete');
        Route::get('discussions/{discussion}/comments/{comment}/history', 'CommentController@history')->name('.discussions.comments.history');

        // Actions
        Route::get('actions', 'ActionController@index')->name('.actions.index');
        Route::get('actions/create', 'ActionController@create')->name('.actions.create');
        Route::post('actions/create', 'ActionController@store')->name('.actions.store');
        Route::get('actions/json', 'ActionController@indexJson')->name('.actions.index.json');
        Route::get('actions/ical', 'IcalController@group')->name('.actions.index.ical');
        Route::get('actions/{action}', 'ActionController@show')->name('.actions.show');
        Route::get('actions/{action}/edit', 'ActionController@edit')->name('.actions.edit');
        Route::post('actions/{action}', 'ActionController@update')->name('.actions.update');
        Route::get('actions/{action}/delete', 'ActionController@destroyConfirm')->name('.actions.deleteconfirm');
        Route::delete('actions/{action}/delete', 'ActionController@destroy')->name('.actions.delete');
        Route::get('actions/{action}/history', 'ActionController@history')->name('.actions.history');



        // Files
        Route::get('files', 'FileController@index')->name('.files.index');
        Route::get('files/create', 'FileController@create')->name('.files.create');
        Route::post('files/create', 'FileController@store')->name('.files.store');
        Route::get('files/createlink', 'FileController@createLink')->name('.files.createlink');
        Route::post('files/createlink', 'FileController@storeLink')->name('.files.storelink');
        Route::get('files/{file}', 'FileController@show')->name('.files.show');
        Route::get('files/{file}/edit', 'FileController@edit')->name('.files.edit');
        Route::post('files/{file}', 'FileController@update')->name('.files.update');
        Route::get('files/{file}/delete', 'FileController@destroyConfirm')->name('.files.deleteconfirm');
        Route::delete('files/{file}/delete', 'FileController@destroy')->name('.files.delete');

        Route::get('files/{file}/download', 'FileDownloadController@download')->name('.files.download');
        Route::get('files/{file}/thumbnail', 'FileDownloadController@thumbnail')->name('.files.thumbnail');
        Route::get('files/{file}/preview', 'FileDownloadController@preview')->name('.files.preview');

        // Members
        Route::get('users', 'UserController@index')->name('.users.index');

        // Maps
        Route::get('map', 'MapController@map')->name('.map');
        Route::get('map/embed', 'MapController@embed')->name('.map.embed');


    });

    // Users
    Route::get('users/{user}', 'UserController@show')->name('users.show');

    Route::get('users/{user}/cover', 'UserController@cover')->name('users.cover');
    Route::get('users/{user}/avatar', 'UserController@avatar')->name('users.avatar');

    Route::get('users/{user}/sendverification', 'UserController@sendVerificationAgain')->name('users.sendverification');

    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::post('users/{user}', 'UserController@update')->name('users.update');

    Route::get('users/{user}/contact', 'UserController@contactForm')->name('users.contactform');
    Route::post('users/{user}/contact', 'UserController@contact')->name('users.contact');





    // Search
    Route::get('search', 'SearchController@index');

    // External cron
    // call/curl/wget yoururl/cron every 5 minutes to have at least email notifiations sent
    // only use this if laravel scheduler is not supoprted by your hosting provider
    // this call is rate limited to one attempt each minute
    Route::group(['middleware' => 'throttle:1'], function () {
        Route::get('cron', function () {
            $exitCode = Artisan::call('notifications:send');

            return $exitCode;
        });
    });

    /***************** ADMIN STUFF **************/
    /*
    Altough we want as little admin so called "rights" or "power" some stuff must be handled by a small group of trusted people like:
    - group creation (that is even questionable, and not yet the case - we want self service)
    - homepage introduction text
    */

    Route::group(['middleware' => ['admin']], function () {
        Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        Route::get('admin/settings', 'Admin\SettingsController@index');
        Route::post('admin/settings', 'Admin\SettingsController@update');

        Route::resource('admin/user', 'Admin\UserController');
        Route::get('admin/insights', 'InsightsController@forAllGroups');
    });


});
