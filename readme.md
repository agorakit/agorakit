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

## Official Documentation

Not yet, but have a look in the documentation folder :-)

## Contributing

Contact me

## Security Vulnerabilities

Contact me

### License

This tool is released under the GPL licence
