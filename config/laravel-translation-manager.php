<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */
    'route' => [
        'prefix' => 'translations',
        'middleware' => ['web', 'admin'],
    ],
    /**
     * Specify the locale that is used for creating the initial translation strings. This locale is considered
     * to be the driver of all other translations.
     *
     * @type string
     */
    'primary_locale' => 'en',
    /**
     * Specify any additional locales that you want to be shown even if they have no translation files or translations in the database
     *
     * @type array of strings
     */
    'locales' => [
        'en',
    ],
    /**
     * Specify locales that you want to show in the web interface, if empty or not provided then all locales in the database
     * will be shown
     *
     * @type array of strings
     */
    'show_locales' => [ 'en', 'fr','de', 'nl'
    ],
    /**
     * Specify the prefix used for all cookies, session data and cache persistence.
     *
     * @type string
     */
    'persistent_prefix' => 'g2Lu2pyz8QcVrxhL32eN',
    /**
     * Enable management of translations beyond just editing and command line manipulations
     *
     * @type boolean
     */
    'admin_enabled' => true,
    /**
     * Enable management of translations for editors by locales
     *
     * Only applies to users that are not translation admins
     *
     * If not defined then by locale access is disabled and all locales can be
     * modified by any editor.
     *
     * @type boolean
     */
    'user_locales_enabled' => false,
    /**
     * Enable markdown translation to html on the fly
     *
     * With this option set to a suffix string, all keys that end in this suffix, will
     * have their text converted to HTML via facade \Markdown::convertToHtml(translationText)
     * graham-cambell/Laravel-Markdown package serves this purpose well.
     *
     * The resulting HTML text will be saved in a key, with the suffix removed.
     *
     * This conversion is ONLY done when the translation for the markdown key is modified
     * via the web UI. Keys already in the translation files or in the database are assumed to
     * be properly converted.
     *
     * Set this to a reasonable suffix that will not conflict with normal keys in your application
     * and with laravel translation key convention.
     *
     * Do not use '.md' or anything with a period. It denotes a nested array key for translations.
     * Do not use multi-byte characters in the suffix. non-multi-byte string operations are used in manipulating the key.
     * Do not use keys that will cause issues in jQuery because keys are used to create jQueries with keys assumed to be valid element id's
     * Do not use colons since these are used to delimit package translation groups
     * You get the picture. Try a suffix out and if it works for you great. If not try another one
     *
     * Some options that work are: '-md', '--md', '-md-'
     *
     * @type string
     */
    'markdown_key_suffix' => '',
    /**
     * No Longer used. Must implement 'ltm-editors-list' ability that will return true
     * if the user can manage per locale access with an array of objects with: id, email,
     * and name fields that correspond to the list of users to be displayed in the web UI
     * to allow the current admin user to manage per locale access.
     *
     */
    //'user_list_provider' => null,
    /**
     * Specify export formatting options:
     *
     * PRESERVE_EMPTY_ARRAYS - preserve first level translations that are empty arrays
     * USE_QUOTES - use " instead of ' for wrapping strings
     * USE_HEREDOC - use <<<'TEXT' for wrapping string that contain \n
     * USE_SHORT_ARRAY - use [] instead of array() for arrays
     * SORT_KEYS - alphabetically sort keys withing an array
     *
     * @type string | array
     */
    'export_format' => array(
        'PRESERVE_EMPTY_ARRAYS',
        //'USE_QUOTES',
        'USE_HEREDOC',
        'USE_SHORT_ARRAY',
        'SORT_KEYS',
    ),
    /**
     * Enable mismatch dashboard
     *
     * @type boolean
     */
    'mismatch_enabled' => false,
    /**
     * Exclude specific groups from Laravel Translation Manager.
     * This is useful if, for example, you want to avoid editing the official Laravel language files.
     *
     * @type array
     */
    'exclude_groups' => array(
        //'pagination',
        //'reminders',
        //'validation',
    ),
    /**
     * Exclude specific groups from Laravel Translation Manager in page edit mode.
     * This is useful for groups that are used exclusively for non-display strings like page titles and emails
     *
     * @type array
     */
    'exclude_page_edit_groups' => array(
        //'page-titles',
        //'reminders',
        //'validation',
    ),
    /**
     * determines whether missing keys are logged
     *
     * @type boolean
     */
    'log_missing_keys' => false,

    /**
     * determines whether usage of keys is logged, requires missing keys to be logged too
     *
     * @type boolean
     */
    'log_key_usage_info' => false,

    /**
     * determines one out of how many user sessions will have a chance to log missing keys
     * since the operation hits the database for every missing key you can limit this by setting a
     * higher number depending on the traffic load to your site.
     *
     * @type int
     *
     * 1 - means every user
     * 10 - means 1 in 10 users
     * 100 - 1 in a 100 users
     * 1000 ....
     *
     */
    'missing_keys_lottery' => 100, // 1 in 100 of users will have the missing translation keys logged.

    /**
     * @type int        0 - as usual, write out files and set status for translations to SAVED,
     *
     *                  1 - on publish will only copy value to saved_value in the database and set the status to SAVED_CACHED
     *                  and add the changed keys to the translator cache so that the correct translation will be used. Used to
     *                  publish translations on production cluster or where write access to lang directory is not available.
     *
     *                  2 - write out files but act as if doing in database publish only, this setting is useful for accessing
     *                  translations from a local dev server to a production database for the purpose of updating translation files
     *                  for deployment. Lets you create the translation files but leaves the translations in the database in a state
     *                  where they will continue to be served up with the latest published version, not the outdated file versions.
     *
     *                  to be used by clustered systems where the translation files are determined at deployment and publishing
     *                  on one system does no good to the rest of the cluster.
     */
    'indatabase_publish' => 0,

    /**
     * @type array      list of alternate database connections and their properties indexed by app()->environment() value,
     *                  default connection settings are taken from config, so only add alternate connections
     *
     *                  If user_list_connection is missing, null or empty then the connection will also be used
     *                  to obtain the user list for user locale management, otherwise the given connection name will be user
     *                  to obtain the user list when managing translations on this connection.
     *
     *                  description is used to display the connection name, default connection is displayed as 'default' in
     *                  the web interface.
     *
     *                  indatabase_publish of:
     *                  0 - means use files only.
     *                  1 - means use cache for publishing modifications. No files are written out
     *                  2 - means use cache for publishing modifications but also write out the files.
     *                      useful for publishing to files while leaving all flags in the database as
     *                      they would be after publishing only to cache.
     */
    'db_connections' => array(
        //'local' => array(
        //    'mysql_prd' => array(
        //        'description' => 'production',
        //        'indatabase_publish' => 2,
        //    ),
        //),
    ),

    /**
     * used to provide an alternate default connection name for translation
     * tables
     *
     * @type string     connection name to use for the default connection
     *
     * if blank, null or not defined then default connection will be used.
     *
     */

    'default_connection' => null,

    /**
     * used to provide the Yandex key for use in automatic Yandex translations
     *
     * @type string     Yandex translation key
     *
     * This key is free to obtain and use but is required to enable Yandex translations. Visit: https://tech.yandex.com/translate/
     *
     */

    'yandex_translator_key' => env('YANDEX_TRANSLATOR_KEY'),
    /**
     * used to provide configuration on where the translation files are stored and where to write them out.
     *
     * This configuration provides the difference in layout between Laravel 4 & 5.
     * It also can be used to include language files from vendor directories without having to export them to the
     * standard lang/ directory for those cases where these files are modified directly so that a git commit can
     * be done in-place.
     *
     * It can also be used to include lang/ files from projects in the workbench subdirectory for Laravel 4.2
     * or Laravel 5 even though Laravel 5 does not support workbench subdirectory the same way it does in
     * version 4.2 but it is easy to configure composer.json to include
     *
     * array keys:
     *
     * 'path'           - the root path for this element's language definitions, Any placeholder values placeholder
     *                  will take the name from the part inside {} and the value from the actual path found on the disk.
     *
     *
     * 'lang'           - defines the location of the application's standard language files relative to the root
     *                  of the application. Laravel 4.2 '/app/lang', 5.x '/resources/lang'.
     *
     * 'packages'        - defines the location of the package override directory
     *
     * 'workbench'       - defines the location of the packages you are including in the project which are also
     *                  the source of the package that you are developing.
     *
     *
     * values:          if the value is a string then it is assumed to be a 'file' spec, if it is an array then the keys
     *                  in the array should correspond to expected values as below. Any missing keys will be added as
     *                  follows:
     *
     *                  lang        - 'db_group' => '{group}'
     *                              - 'root' => ''
     *                        (4.2) - 'file' => '/app/lang/{locale}/{group}'
     *                        (5.x) - 'file' => '/resources/lang/{locale}/{group}'
     *
     *                  packages    - 'db_group' => '{package}::{group}'
     *                              - 'root' => ''
     *                        (4.2) - 'file' => '/app/lang/packages/{locale}/{package}/{group}'
     *                        (5.x) - 'file' => '/resources/lang/vendor/{package}/{locale}/{group}'
     *
     *                  workbench   - 'db_group' => 'wbn:{vendor}.{package}::{group}'
     *                              - 'root' => '/workbench/{vendor}/{package}'
     *                              - 'include' => '/' // which means all vendor/package combinations
     *                        (4.2) - 'file' => 'src/lang/{locale}/{group}'
     *                        (5.x) - 'file' => 'resources/lang/{locale}/{group}'
     *
     *                  vendor      - 'db_group' => 'vnd:{vendor}.{package}::{group}'
     *                              - 'root' => '/vendor/{vendor}/{package}'
     *                              - 'include' => [] // which means no vendor/package combinations will be included
     *                        (4.2) - 'file' => 'src/lang/{locale}/{group}'
     *                        (5.x) - 'file' => 'resources/lang/{locale}/{group}'
     *
     *
     *                  The above sections have other defaults that will be added when the config file is processed. The other
     *                  sections will only have their 'include' converted from a string to an array, or an empty array
     *                  will be added
     *
     *                  array values can have the following:
     *
     *                  'root' - the path spec for the path common between versions and packages
     *
     *                  'file' - the path spec, when appended to 'root' that will result in the language file once .php
     *                           is appended to it. see above
     *
     *                  The combined 'root' and 'file' strings are appended, and the resulting path searched with
     *                  {vendor}, {package}, {group}, {locale} being variables that will match any directory at that
     *                  position and the actual directory name will be used as the value for the corresponding variable
     *                  in deriving the 'db_group' value. The last part of the path is expected to be a variable of the
     *                  form {name} and its value will be the name of the file (minus the .php extension). If the last
     *                  element in the path is {group} then it will also contain . separated sub-directories. Any other
     *                  named element will only match files.
     *
     *                  'include' - valid for all types other than 'lang' and 'packages', it is a string or an array
     *                  defining package combinations to include, each
     *                  element in the array is assumed to be {vendor}/{package}, if either {vendor} or {package} is
     *                  missing or * then all such sub-directories will be included. Example:
     *
     *                  'vsch/'  - will include all packages under vsch/
     *
     *                  '/laravel-translation-manager' - all packages of that name regardless of the vendor will be
     *                  included.
     *
     *                  '/' - will include all vendors and packages.
     *
     *                  NOTE: if the array is empty then no vendors will be included. This is the default for
     *                  'vendors' and '/' default for 'workbench' which will include language files
     *                  for all workbench packages.
     *
     * @type array
     *
     * Please read above before changing.
     */
    'language_dirs' => array(
        'lang' => '/resources/lang/{locale}/{group}',
        'packages' => '/resources/lang/vendor/{package}/{locale}/{group}',
        'workbench' => [
            'include' => '*/*',
            'root' => '/workbench/{vendor}/{package}',
            'files' => 'resources/lang/{locale}/{group}',
        ],
        'vendor' => [
            'include' => [],
            'root' => '/vendor/{vendor}/{package}',
            'files' => 'resources/lang/{locale}/{group}',
        ],
        /*
         * add packages that need special mapping to their language files because they don't use the standard Laravel
         * layout for the version that you are using or just plain not Laravel layout. Add '__merge' key with names of
         * sections where the package should be attempted to be merged.
         *
         * These will be merged with vendor or workbench type to get the rest of the config information.
         * The sections will be checked in the order listed in the __merge entry. The first section whose include
         * accepts the vendor/package used here, will be used. All the other sections, listed in __merge, will be ignored.
         *
         * Since vendor section requires opt-in, it is listed first, if this package is included then
         * it will be a vendor type.
         *
         * NOTE: Regardless of whether the directory exists or not under vendor if the vendor section includes this
         * package, then it will be expected to be in the vendor directory. If it is not then no language files will be
         * loaded for it. Therefore only include in vendor section if it is not actually located in workbench.
         */
        'caouecs/laravel-lang' => [
            '__merge' => ['vendor', 'workbench',],
            'files' => 'src/{locale}/{group}',
        ],
        /*
         * This one requires a very different definition. The file names are the locale.php, therefore more guts are
         * exposed when defining the mapping of this one. Including a hard-coded value for the {group} since the only
         * other option is to replace Lang dir with {group}, but then the group will be called Lang, seems a bit out of
         * place from the rest of the packages. So there is a way to just hard code the group to any string.
         */
        'nesbot/carbon' => [
            '__merge' => ['vendor', 'workbench',],
            'files' => 'src/Carbon/Lang/{locale}',
            'vars' => [
                '{group}' => 'carbon',
            ],
        ],
    ),
    /**
     *
     * Provide the prefix for the root of the zip file
     * if a path from language_dirs does not start with this prefix then language files exported
     * for that part will include the full path. Therefore define the most common root path
     * / means application root.
     *
     */
    'zip_root' => '/resources',

);
