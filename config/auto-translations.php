<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language Files Path
    |--------------------------------------------------------------------------
    |
    | The base path where your language files are stored. By default, it's
    | the 'lang' directory. You can change this to match your application's
    | structure.
    |
    */

    'lang_path' => lang_path(),

    /*
    |--------------------------------------------------------------------------
    | Default Translation Driver
    |--------------------------------------------------------------------------
    |
    | The default translation driver to use when none is specified. You can
    | set this to any of the drivers defined in the 'drivers' array below.
    |
    */

    'default_driver' => env('TRANSLATION_DEFAULT_DRIVER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Source Language Code
    |--------------------------------------------------------------------------
    |
    | The default source language code of your application. This will be used
    | as the source language for translations unless specified otherwise.
    |
    */

    'source_language' => env('TRANSLATION_SOURCE_LANGUAGE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Available Translation Drivers
    |--------------------------------------------------------------------------
    |
    | Configure as many translation drivers as you wish. Each driver should
    | have a unique name and its own configuration settings.
    |
    */

    'drivers' => [

        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'temperature' => (float) env('OPENAI_TEMPERATURE', 0.7),
            'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 4096),
            'http_timeout' => (int) env('OPENAI_HTTP_TIMEOUT', 300),
        ],

        'google' => [
            'api_key' => env('GOOGLE_API_KEY'),
        ],

        'deepl' => [
            'api_key' => env('DEEPL_API_KEY'),
            'api_url' => env('DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate'),
        ],

        'deepseek' => [
            'api_key'      => env('DEEPSEEK_API_KEY'),
            'api_url'      => env('DEEPSEEK_API_URL', 'https://api.deepseek.com'),
            'model'        => env('DEEPSEEK_MODEL', 'deepseek-chat'),
            'max_tokens'   => (int) env('DEEPSEEK_MAX_TOKENS', 2000),
            'temperature'  => (float) env('DEEPSEEK_TEMPERATURE', 0),
            'http_timeout' => (int) env('DEEPSEEK_HTTP_TIMEOUT', 60),
        ],

        'ollama' => [
            'api_url' => env('OLLAMA_API_URL', 'http://localhost:11434/v1'),
            'model' => env('OLLAMA_MODEL', 'llama3'),
            'max_tokens' => env('OLLAMA_MAX_TOKENS', 2048),
            'temperature' => 0,
            'http_timeout' => 60,
        ],
        
        'my_custom_driver' => [
            'class' => App\Drivers\MyCustomDriver::class,
            'api_key' => env('MY_CUSTOM_API_KEY'),
            // ...
        ],
    ],
];
