# It is important to keep an up to date installation of Agorakit
We try to keep the master branch always in a good, safe, and working condition (this is called a "rolling release" model). That means that tests passes and that you get the latest features directly from the master branch.

## Make a backup
Make a backup of your SQL database in case something goes wrong.


## Proceed with the update
You can at anytime do this to update your install :

```
$ php artisan down
$ git pull
$ composer install
$ php artisan migrate
$ php artisan up
```

## Update script 
There is also a helper script that does it for you :
`$ ./update`
