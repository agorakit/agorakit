<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */
    'dir' => ['files'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => ['prefix' => 'admin/elfinder', 'middleware' => ['web', 'admin']], //Set to null to disable middleware filter

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    //'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',
    'access' => '\App\Http\Controllers\FileController::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => [],

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [],

    /*
    |--------------------------------------------------------------------------
    | Root Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with every root by default.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1#root-options
    |
    */
    'root_options' => [
        'disabled'      => ['rm'],
        'alias'         => 'home',
        'uploadMaxSize' => '20M',
        /*'uploadOrder' => 'Allow,Deny',*/
        /*'uploadAllow' => ['audio', 'video', 'image', 'application/msword', 'text/plain', 'application/pdf'],*/
        'uploadDeny' => ['text/php', 'text/x-php', 'application/php', 'application/x-php', 'application/x-httpd-php', 'application/x-httpd-php-source'], // TODO security issues
        // todo see here : https://github.com/Studio-42/elFinder/wiki/Simple-file-permissions-control

        /*
        'attributes'    =>
        [
            'pattern' => '/.(Pictures+)/', // Access for Folders named "Pictures" and his subfolders
            'read'  => false,
            'write' => true,
            'locked' => true
        ]
        */

    ],

];
