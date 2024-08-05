# Update your installation
It is important to keep an up to date installation of Agorakit. The process is safe

We try to keep the master branch always in a good, safe, and working condition (this is called a "rolling release" model).

That means that tests passes and that you get the latest features directly from the master branch.

!!! warning 
    Make a backup of your SQL database in case something goes wrong. Make a backup of all your files as well. Make two backups even, and store them on a separate machine.

# Choose your poison
You can choose between an automated update procedure from the command line, or a step by step to update manually :

## Update script for automated updates
There is a helper script that does the update for you :

```
./update
```

!!! warning
    Beware that the script will migrate your database without asking for confirmation. Always make a backup of the database just in case something goes wrong.


## Proceed with the update manually
You can at anytime do this to update your install :

```
php artisan down
git pull
composer install
php artisan migrate
php artisan up
```


## If something goes wrong
Restore your database backup and git checkout a previous (working) version. Then re-run composer install.

Contact us if an update fails (it never happened so this kind of failure is highly interesting information for the project).

# Version Specific Instructions
## Upgrade to 1.5
After the normal update you might get an error mentioning duplicate username key in user table.

Run `php artisan agorakit:enforceuniqueusernames` to fix the issue
Then re-run the update script.

This happens only on large installs and can be run multiple times without problem. It's a future proof fix for this issue.

!!! note
    Most one time upgrades like the one mentionned above are now made inside migrations to reduce the amount of documentation you need to read in order to properly upgrade your installation. Sit back, relax, and enjoy automation at work :-)
