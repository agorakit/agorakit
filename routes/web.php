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
    Route::get('discussions', 'DiscussionController@index')->name('discussions');
    Route::get('users', 'UserController@index')->name('users');
    Route::get('files', 'FileController@index')->name('files');
    Route::get('map', 'MapController@index')->name('map');
    Route::get('map.geojson', 'MapController@geoJson')->name('map.geojson');

    Route::get('agenda', 'ActionController@index')->name('agenda');
    Route::get('agenda/json', 'ActionController@indexJson')->name('agenda.json');
    Route::get('agenda/ical', 'IcalController@index')->name('agenda.ical');


    Route::get('tags', 'TagController@index')->name('tags.index');
    Route::get('tags/{tag}', 'TagController@show')->name('tags.show');

    /* Pages */
    Route::get('pages/help', 'PageController@help')->name('pages.help');

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

    /////////////////// COMMON STUFF (Dashboard & overview) /////////////////////

    // application homepage, lists all groups on the server
    Route::get('groups', 'GroupController@index')->name('groups.index');
    Route::get('groups/create', 'GroupController@create')->name('groups.create');
    Route::post('groups/create', 'GroupController@store')->name('groups.store');

    // Groups : what everyone can see, homepage and covers
    Route::get('groups/{group}', 'GroupController@show')->name('groups.show');
    Route::get('groups/{group}/cover/small', 'GroupCoverController@small')->name('groups.cover.small');
    Route::get('groups/{group}/cover/medium', 'GroupCoverController@medium')->name('groups.cover.medium');
    Route::get('groups/{group}/cover/large', 'GroupCoverController@large')->name('groups.cover.large');

    // Invite system for groups
    Route::get('groups/{group}/invite/confirm/{token}', 'InviteController@inviteConfirm')->name('groups.invite.confirm');
    Route::post('groups/{group}/invite/confirm/{token}', 'InviteController@inviteRegister')->name('groups.invite.register');

    // Join and apply for a group
    Route::get('groups/{group}/join', 'MembershipController@create')->name('groups.membership.create');
    Route::post('groups/{group}/join', 'MembershipController@store')->name('groups.membership.store');

    // General discussion create route
    Route::get('discussions/create', 'GroupDiscussionController@create')->name('discussions.create');
    Route::post('discussions/create', 'GroupDiscussionController@store')->name('discussions.store');

    // General action create route
    Route::get('actions/create', 'GroupActionController@create')->name('actions.create');
    Route::post('actions/create', 'GroupActionController@store')->name('actions.store');

    // Users

    Route::get('users/{user}', 'UserController@show')->name('users.show');

    Route::get('users/{user}/cover/{size}', 'UserCoverController@show')->name('users.cover');

    Route::get('users/{user}/sendverification', 'UserController@sendVerificationAgain')->name('users.sendverification');

    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::post('users/{user}', 'UserController@update')->name('users.update');

    Route::get('users/{user}/delete', 'UserController@destroy')->name('users.delete');
    Route::delete('users/{user}/delete', 'UserController@destroy')->name('users.delete');

    Route::get('users/{user}/contact', 'UserController@contactForm')->name('users.contactform');
    Route::post('users/{user}/contact', 'UserController@contact')->name('users.contact');

    // Reactions on comments

    Route::get('comments/{comment}/react/{context}', 'CommentReactionController@store');
    Route::get('comments/{comment}/unreact', 'CommentReactionController@destroy');


    //////////////////////////// GROUPS /////////////////////////////////////////


    // Groups : only members (or everyone if a group is public)
    Route::group(['middleware' => 'public', 'as' => 'groups', 'prefix' => 'groups/{group}'], function () {

        // Crud stuff
        Route::get('edit', 'GroupController@edit')->name('.edit');
        Route::post('edit', 'GroupController@update')->name('.update');
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

        // preferences and leave group
        //Route::get('preferences', 'MembershipController@edit')->name('.mymembership.edit');
        //Route::post('preferences', 'MembershipController@update')->name('.mymembership.update');
        Route::get('leave', 'MembershipController@destroyConfirm')->name('.mymembership.deleteconfirm');
        Route::post('leave', 'MembershipController@destroy')->name('.mymembership.delete');

        // edit membership
        Route::get('membership/{membership?}', 'MembershipController@edit')->name('.membership.edit');
        Route::post('membership/{membership?}', 'MembershipController@update')->name('.membership.update');

        // Stats
        Route::get('insights', 'GroupInsightsController@index')->name('.insights');

        // Membership mass admin (add multiple users at once to a groupe)
        Route::get('massmembership/create', 'MassMembershipController@create')->name('.massmembership.create');
        Route::post('massmembership/store', 'MassMembershipController@store')->name('.massmembership.store');

        // Invites
        Route::get('invite', 'InviteController@invite')->name('.invite.form');
        Route::post('invite', 'InviteController@sendInvites')->name('.invite');

        // In the case of closed group, we show an how to join message (not in use currently)
        Route::get('howtojoin', 'MembershipController@howToJoin')->name('.howtojoin');

        // Members
        Route::get('users', 'MembershipController@index')->name('.users.index');

        // Maps
        Route::get('map', 'GroupMapController@index')->name('.map');
        Route::get('map.geojson', 'GroupMapController@geoJson')->name('.map.geojson');

        Route::get('{type}/{id}/tag', 'TagController@edit')->name('.tags.edit');
        Route::post('{type}/{id}/tag', 'TagController@update')->name('.tags.store');

        // Discussions
        Route::get('discussions', 'GroupDiscussionController@index')->name('.discussions.index');
        Route::get('discussions/create', 'GroupDiscussionController@create')->name('.discussions.create');
        Route::post('discussions/create', 'GroupDiscussionController@store')->name('.discussions.store');

        Route::get('discussions/{discussion}', 'GroupDiscussionController@show')->name('.discussions.show');
        Route::get('discussions/{discussion}/edit', 'GroupDiscussionController@edit')->name('.discussions.edit');
        Route::post('discussions/{discussion}', 'GroupDiscussionController@update')->name('.discussions.update');

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

        // Actions
        Route::get('actions', 'GroupActionController@index')->name('.actions.index');
        Route::get('actions/create', 'GroupActionController@create')->name('.actions.create');
        Route::post('actions/create', 'GroupActionController@store')->name('.actions.store');
        Route::get('actions/json', 'GroupActionController@indexJson')->name('.actions.index.json');
        Route::get('actions/ical', 'GroupIcalController@index')->name('.actions.index.ical');
        Route::get('actions/{action}', 'GroupActionController@show')->name('.actions.show');
        Route::get('actions/{action}/edit', 'GroupActionController@edit')->name('.actions.edit');
        Route::post('actions/{action}', 'GroupActionController@update')->name('.actions.update');
        Route::get('actions/{action}/delete', 'GroupActionController@destroyConfirm')->name('.actions.deleteconfirm');
        Route::delete('actions/{action}/delete', 'GroupActionController@destroy')->name('.actions.delete');
        Route::get('actions/{action}/history', 'GroupActionController@history')->name('.actions.history');

        // Action (un)attending
        Route::get('actions/{action}/attend', 'ActionUserController@create')->name('.actions.attend');
        Route::get('actions/{action}/unattend', 'ActionUserController@destroyConfirm')->name('.actions.unattend');

        Route::post('actions/{action}/attend', 'ActionUserController@store')->name('.actions.attend.store');
        Route::post('actions/{action}/unattend', 'ActionUserController@destroy')->name('.actions.unattend.store');

        // Files
        Route::get('files', 'GroupFileController@index')->name('.files.index');
        Route::get('files/create', 'GroupFileController@create')->name('.files.create');
        Route::post('files/create', 'GroupFileController@store')->name('.files.store');
        Route::get('files/createlink', 'GroupFileController@createLink')->name('.files.createlink');
        Route::post('files/createlink', 'GroupFileController@storeLink')->name('.files.storelink');
        Route::get('files/{file}', 'GroupFileController@show')->name('.files.show');
        Route::get('files/{file}/edit', 'GroupFileController@edit')->name('.files.edit');
        Route::post('files/{file}', 'GroupFileController@update')->name('.files.update');
        Route::get('files/{file}/delete', 'GroupFileController@destroyConfirm')->name('.files.deleteconfirm');
        Route::delete('files/{file}/delete', 'GroupFileController@destroy')->name('.files.delete');

        Route::get('files/{file}/download', 'FileDownloadController@download')->name('.files.download');
        Route::get('files/{file}/thumbnail', 'FileDownloadController@thumbnail')->name('.files.thumbnail');
        Route::get('files/{file}/preview', 'FileDownloadController@preview')->name('.files.preview');


        // Allowed Tags
        Route::get('tags', 'GroupTagController@index')->name('.tags.index');
        Route::get('tags/create', 'GroupTagController@create')->name('.tags.create');
        Route::post('tags/create', 'GroupTagController@store')->name('.tags.store');
        Route::get('tags/{tag}', 'GroupTagController@show')->name('.tags.show');
        Route::get('tags/{tag}/edit', 'GroupTagController@edit')->name('.tags.edit');
        Route::post('tags/{tag}', 'GroupTagController@update')->name('.tags.update');
        Route::get('tags/{tag}/delete', 'GroupTagController@destroyConfirm')->name('.tags.deleteconfirm');
        Route::delete('tags/{tag}/delete', 'GroupTagController@destroy')->name('.tags.delete');

        // Permissions
        Route::get('permissions', 'GroupPermissionController@index')->name('.permissions.index');
        Route::post('permissions', 'GroupPermissionController@update')->name('.permissions.update');

    });

    // Search
    Route::get('search', 'SearchController@index');

    // External cron
    // call/curl/wget yoururl/cron every 5 minutes to have at least email notifiations sent
    // only use this if laravel scheduler is not supoprted by your hosting provider
    // this call is rate limited to one attempt each minute

    /*
    Route::group(['middleware' => 'throttle:1'], function () {
        Route::get('cron', function () {
            $exitCode = Artisan::call('notifications:send');

            return $exitCode;
        });
    });
    */

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
        Route::get('admin/insights', 'Admin\InsightsController@index')->name('admin.insights');

        Route::get('admin/undo', 'UndoController@index')->name('admin.undo');
        Route::get('admin/{type}/{id}/restore', 'UndoController@restore')->name('admin.restore');
    });
});
