<?php

// you can change most of those settings in the admin settings UI

return [
  'name'                          => env('APP_NAME', 'Agorakit'),
  'version'                       => '1.5', // do not update manually except when releasing a new version
  'user_can_create_groups'        => true, // allow normal users to create new groups
  'notify_admins_on_group_create' => true, // as it says :-)
  'inbox_driver' => env('INBOX_DRIVER', null),
  'inbox_host' => env('INBOX_HOST', null),
  'inbox_port' => env('INBOX_PORT', 993),
  'inbox_flags' => env('INBOX_FLAGS', '/imap/ssl/novalidate-cert'),
  'inbox_username' => env('INBOX_USERNAME'),
  'inbox_password' => env('INBOX_PASSWORD'),
  'inbox_prefix' => env('INBOX_PREFIX'),
  'inbox_suffix' => env('INBOX_SUFFIX'),
];
