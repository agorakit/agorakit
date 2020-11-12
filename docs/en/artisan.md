# Useful artisan commands

Agorakit provides some useful [artisan](https://laravel.com/docs/7.x/artisan) commands :

- `agorakit:checkmailbox`             Check the configured email imap server to allow post by email functionality
- `agorakit:cleanupdatabase`         Cleanup the database, delete forever soft deleted models and unverified users older than 30 days
- `agorakit:convertfiles`              Convert the files from the old flat path based storage to the new storage, putting back their initila filename and moving the file to public directory. Use this **once** if you already have files on your install and if your install is older than november 2016.
- `agorakit:convertfolderstotags`      Convert all folders to the new tag based system. Add the tags represneting parent folder to each file
- `agorakit:deletefiles`               Delete files from storage after 30 days of deletion in database
- `agorakit:export`                    Export a group to a zip file (work in progress)
- `agorakit:import`                    Import a group from an Agorakit export zip file (work in progress)
- `agorakit:populatefilesize`          Set filesize in the files table using real filesize from the filesystem. Use this if you installed agorakit before november 2017
- `agorakit:sendnotifications`         Sends all the pending notifications to all users who requested it. This might take time. Call this frequently to avoid trouble
- `agorakit:sendreminders`             Sends all reminders to participants who asked for it - call exactly every 5 minutes, no more, no less :-)

!>Most of those commands are already used by the cron job, so they don't need to be called manually. But they might be useful for debugging and development.
