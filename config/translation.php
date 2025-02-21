<?php

return [
    'key'            => env('TRANSLATIONIO_KEY'),
    'source_locale'  => 'en',
    'target_locales' => ['nl', 'eo', 'fr', 'de', 'it', 'ru', 'es'],

    /* Directories to scan for Gettext strings */
    'gettext_parse_paths' => ['app', 'resources'],

    /* Where the Gettext translations are stored */
    'gettext_locales_path' => 'resources/lang/gettext',

    /* Where to find country menu options */
    'country_menu_options_path' => 'resources/lang',
];
