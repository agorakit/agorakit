<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Folders where to search for lemmas
    |--------------------------------------------------------------------------
    |
    | Localization::Missing will search recursively for lemmas in all php files
    | included in these folders. You can use these keywords :
    | - %APP     : the laravel app folder of your project
    | - %BASE    : the laravel base folder of your project
    | - %PUBLIC  : the laravel public folder of your project
    | - %STORAGE : the laravel storage folder of your project
    | No error or exception is thrown when a folder does not exist.
    |
    */
    'folders'                  => [
        '%BASE/resources/views',
        '%APP/Http/Controllers',
        '%APP/Notifications',
        '%APP/Mail',
        '%APP/Mailers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lang file to ignore
    |--------------------------------------------------------------------------
    |
    | These lang files will not be written
    | You can specify :
    | - a family like 'message', 'validation', ...
    | - a specific file path like '/resources/lang/de/cms.php'
    |
    */
    'ignore_lang_files'        => [
        'validation',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lang folder
    |--------------------------------------------------------------------------
    |
    | You can overwrite where is located your lang folder
    | If null or missing, Localization::Missing will search :
    | - first in app_path() . DIRECTORY_SEPARATOR . 'lang',
    | - then  in base_path() . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang',
    |
    */
    'lang_folder_path'         => null,

    /*
    |--------------------------------------------------------------------------
    | Methods or functions to search for
    |--------------------------------------------------------------------------
    |
    | Localization::Missing will search lemmas by using these regular expressions
    | Several regular expressions can be used for a single method or function.
    |
    */
    'trans_methods'            => [
        'json'         => [
            '@__\(\s*(\'.*\')\s*(,.*)*\)@U',
            '@__\(\s*(".*")\s*(,.*)*\)@U',
        ],
        'trans'        => [
            '@trans\(\s*(\'.*\')\s*(,.*)*\)@U',
            '@trans\(\s*(".*")\s*(,.*)*\)@U',
        ],
        'Lang::Get'    => [
            '@Lang::Get\(\s*(\'.*\')\s*(,.*)*\)@U',
            '@Lang::Get\(\s*(".*")\s*(,.*)*\)@U',
            '@Lang::get\(\s*(\'.*\')\s*(,.*)*\)@U',
            '@Lang::get\(\s*(".*")\s*(,.*)*\)@U',
        ],
        'trans_choice' => [
            '@trans_choice\(\s*(\'.*\')\s*,.*\)@U',
            '@trans_choice\(\s*(".*")\s*,.*\)@U',
        ],
        'Lang::choice' => [
            '@Lang::choice\(\s*(\'.*\')\s*,.*\)@U',
            '@Lang::choice\(\s*(".*")\s*,.*\)@U',
        ],
        '@lang'        => [
            '@\@lang\(\s*(\'.*\')\s*(,.*)*\)@U',
            '@\@lang\(\s*(".*")\s*(,.*)*\)@U',
        ],
        '@choice'      => [
            '@\@choice\(\s*(\'.*\')\s*,.*\)@U',
            '@\@choice\(\s*(".*")\s*,.*\)@U',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | JSON languages
    |--------------------------------------------------------------------------
    |
    | You must set languages you want to generate in JSON format
    |
    | JSON langages are supported since Laravel 5.4
    | https://laravel.com/docs/5.4/localization#using-translation-strings-as-keys
    |
    */
    'json_languages'           => [
        'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Keywords for obsolete check
    |--------------------------------------------------------------------------
    |
    | Localization::Missing will search lemmas in existing lang files.
    | Then it searches in all PHP source files.
    | When using dynamic or auto-generated lemmas, you must tell Localization::Missing
    | that there are dynamic because it cannot guess them.
    |
    | Example :
    |   - in PHP blade code : <span>{{{ trans( "message.user.dynamo.$s" ) }}}</span>
    |   - in lang/en.message.php :
    |     - 'user' => array(
    |         'dynamo' => array(
    |           'lastname'  => 'Family name',
    |           'firstname' => 'Name',
    |           'email'     => 'Email address',
    |           ...
    |   Then you can define in this parameter value dynamo for example so that
    |   Localization::Missing will not exclude lastname, firstname and email from
    |   translation files.
    |
    */
    'never_obsolete_keys'      => [
        'dynamic',
        'fields',
    ],

    /*
    |--------------------------------------------------------------------------
    | Obsolete lemma prefix
    |--------------------------------------------------------------------------
    |
    | If you want to keep obsolete lemma in your lang file, they will be stored
    | in a sub-array. If not stored in a sub-array, LLH will not be able to
    | separate child lemma.
    |
    | eg : message.section.dog is used
    |      message.section.cat is not used anymore
    | then : message.section.dog will be kept
    |        LLH:obsolete.message.section.cat will be generated
    |
    | : is used in the key because : is a special char in laravel lang lemma and
    | : are automatically not scanned by LLH.
    |
    | Do not change this parameter between 2 commands launch because LLH will not
    | be able to find obsolete lemma in the second pass and you will need to
    | clean up obsolete lemma manually
    |
    */
    'obsolete_array_key'       => 'LLH:obsolete',

    /*
    |--------------------------------------------------------------------------
    | Editor
    |--------------------------------------------------------------------------
    |
    | when using option editor, package will use this command to open your files
    |
    */
    'editor_command_line'      => '/Applications/Sublime\\ Text.app/Contents/SharedSupport/bin/subl',

    /*
    |--------------------------------------------------------------------------
    | Code Style
    |--------------------------------------------------------------------------
    |
    | You can set rules for the code style applied to the generated lang files.
    | Available rules are here : https://github.com/FriendsOfPHP/PHP-CS-Fixer
    |
    | eg: [
    |     'array_syntax' => ['syntax' => 'short'],
    |     '@PSR2'        => true,
    | ];
    |
    */
    'code_style'               => [
        'rules' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Translator
    |--------------------------------------------------------------------------
    |
    | Use the Microsoft translator by default. This is the only available translator now
    |
    */
    'translator'               => 'Microsoft',

    /*
    |--------------------------------------------------------------------------
    | Translators configuration
    |--------------------------------------------------------------------------
    |
    | Microsoft
    |
    | #### default_language
    |
    | Set the default language used in your PHP code. If set to null, the translator
    | will try to guess it. The default language in your code is the language you use
    | in this PHP line for example :
    |
    | trans( 'message.This is a message in english' );
    |
    | Supported languages are : ar, bg, ca, cs, da, de, el, en, es, et, fa, fi, fr,
    | he, hi, ht, hu, id, it, ja, ko, lt, lv, ms, mww, nl, no, pl, pt, ro, ru, sk,
    | sl, sv, th, tr, uk, ur, vi, zh-CHS, zh-CHT
    |
    | #### client_key
    |
    | Package can automatically translate your lemma. Follow instructions to retrieve
    | your client key here :
    | https://github.com/potsky/microsoft-translator-php-sdk#user-content-2-configuration
    |
    | If you don't want to set these credentials here, set both to null and set
    | environment parameters on your computer/server:
    | - LLH_MICROSOFT_TRANSLATOR_CLIENT_KEY
    |
    */
    'translators'              => [
        'Microsoft' => [
            'default_language' => null,
            'client_key'       => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dot notation split
    |--------------------------------------------------------------------------
    |
    | You can set a regex to detect dots in the dot notation.
    |
    | The default behavior is /\\./ and null is a shortcut.
    | I prefer to set it to /\\.(?=[^ .!?])/ which will ignore all dots followed
    | by space, dot, ! and ?
    |
    | This parameter will change nothing if you use the output-flat option of course
    |
    */
    'dot_notation_split_regex' => null,

];
