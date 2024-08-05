# Developing with Agorakit

You want to contribute to the project? Great! Any idea is welcome. 

- Please discuss the bug or feature request in the issue queue first.
- Check the licence (AGPL) to see if it fits your contribution model
- Follow Laravel best practices
- We use Unpoly js for spa like functionality, read a bit about it since it's not as popular as other options (think of it as turbolinks on steroids)
- We use Bootstrap flavored by tabler.io


# Developement server

## Using Laravel Sail
Sail is a docker wrapper to ease local development. It's a really cool and easy way to have a perfect local server for devs.
Laravel sail works out of the box in this project. Read the Sail documentation for more informations. 

This is what I use on my main workstation (linux based) since it's very reproductible.

Basically, `sail up` will start a developement server


## Using artisan serve

If you want to start a local server for development:

    $ php artisan serve

The install will be available to 127.0.0.1:8000

There are a lot of other options, check the laravel doc and ecosystem to have an overview of the options for local developement.

# Seed the DB 
First follow the installation instructions including the creation of sample content using :

    $ php artisan db:seed


# Working on design and css

I ditched all build steps, now everything happens in a flat custom.css file.

All external JS and CSS are served from various CDN's. At some point the files will be re-served from local, when everything will be stabilized, and if there are real benefits of doing so.

No npm, no node, no tailwind, no purge, no minifier, no trouble :)


# Testing your code

Agorakit is tested using the Laravel testing framework.

In order to test, you need to have an existing testing database. Just create an additional empty DB, for instance agorakit_testing and check in the phpunit.xml file that everything matches.

Before comiting code, you should either write more tests (in this case you deserve a cookie). Or at least check that you didn't break anything by simply typing::

    php artisan test

...in the root of your project.

No error should appear (provided that you have everything correctly set up.

We use travis ci to run all those tests on commit so it will be done automatically for you at some point :-)

# Writing tests

Don't hesitate to write tests. We favor well defined tasks an end user would really accomplish, like registering, creating an account, posting, uploading, etc... It has served us very well in the past to spot errors and it really mirrors real use cases. Although we are open to other kind of tests as well...
