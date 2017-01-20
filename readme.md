[![Build Status](https://travis-ci.org/philippejadin/Mobilizator.svg?branch=master)](https://travis-ci.org/philippejadin/Mobilizator)

## Laravel backend & frontend developer wanted
We are looking for help to enhance this collaborative platform called "Mobilizator" used by multiple citizen initiatives (Tout autre chose, Hart Boven Hard, Collectif Roosevelt, Stop-TTIP, etc.). 

- Backend developer with experience of the Laravel framework : add new features to the existing tool, fix bug, write tests, document everything
- Frontend developer with experience of the Laravel framework : the current UI is not pretty, make it brilliant. Fix UX as you see fit.

If you have some time to give to this project, there are already some issues to fix (see. https://github.com/philippejadin/Mobilizator/issues ). Whether you have one hour per day or one hour per month, help is really appreciated! And remember, "talk is silver, code is gold" :-)

All the work is open source (GPL) and will benefit every interested citizen initiative. We already have 100's of users who might benefit from your help.

## Mobilizator

Mobilize crowds efficiently. Allow anyone to create a collaborative group. No admins involved in the process. Manage communication, decision making, membership, files and events.

Facebook for the paranoid inside any of us.

## Requirements
You need a good webhosting provider that provides the following :
- php 5.6 or newer
- mysql
- composer
- git
- the ability to run cron jobs

All those features together are are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal. I successfuly installed everything at alwaysdata.com and combell.com (minus the cron job feature in the case of combell.com, which means you won't have automated email notifications)



## Installation

Currently, you need to know how to install a laravel application. This is perfectly standard and documented. You need composer up and running.

In short :

```
$ git clone https://github.com/philippejadin/Mobilizator.git
$ cp .env.example .env
$ editor .env
$ composer install
$ php artisan key:generate
$ php artisan migrate
```


## Updates
I try to keep the master branch always in a good, safe, and working condition (this is called a "rolling release" model).

You can at anytime do this to update your install :

```
$ php artisan down
$ git pull
$ composer install
$ php artisan migrate
$ php artisan up
```

Create a first user account. In some future release, the first created user will be admin.

If you don't know how all this works, don't setup a production server around this tool without first digging laravel docs.

## Cron

Setup a cron jon in order to have the automated notifications sent to every subscriber

To do so, the laravel docs are pristine clear : https://laravel.com/docs/master/scheduling

## Development

### Option 1 : Using php's built in server

If you want to start a local server for development :
```
$ php artisan serve
```
The install will be available to localhost:8000

### Option 2 : Simplest installation using Vagrant, for development purposes

To use vagrant, you need to install : [Virtual Box](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](https://www.virtualbox.org/wiki/Downloads).

After in the root of your project :

````bash
$ vagrant up
$ vagrant ssh
$ cd mobilizator
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed (if you want the db filled with fake infos)
````

Then you will have access to the project using :

http://192.168.10.12/

#### Modifying your hosts file

You must add the "domains" for your Nginx sites to the hosts file on your machine. The hosts file will redirect requests for your Homestead sites into your Homestead machine. On Mac and Linux, this file is located at /etc/hosts. On Windows, it is located at C:\Windows\System32\drivers\etc\hosts. The lines you add to this file will look like the following:

````
192.168.10.12  loc.participer.toutautrechose.be
192.168.10.12  loc.deelnemen.hardbovenhard.be
````

## Official Documentation

Not yet, but have a look in the documentation folder :-)

## Contributing

Contact me

## Security Vulnerabilities

Contact me

## License

This tool is released under the GPL licence
