## Mobilizator

Mobilize crowds efficiently. Allow anyone to create a collaborative group. No admins involved in the process. Manage communication, decision making, membership, files and events.

Facebook for the paranoid inside any of us.

## Installation

Currently, you need to know how to install a laravel application. This is perfectly standard and documented. You need composer up and running.

In short :

- clone this repository
- copy .env.example to .env and put your db credentials there

```
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed (if you want the db filled with fake infos)
$ php artisan serve
```

The install will be available to localhost:8000

Create a first user account. Set the admin field to 1 in phpmyadmin for this user in the Users table (this is a temporary solution). This user will be able to create groups at [yourinstall]/groups/create and also edit the homepage intro text.


If you don't know how all this works, don't setup a production server around this tool without first digging laravel docs.

## Simplest installation using Vagrant

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

### Modifying your hosts file

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

### License

This tool is released under the GPL licence
