# Developing with Agorakit


You want to contribute to the project? Great!

First follow the installation instructions including the creation of sample content using :

    $ php artisan db:seed



If you want to start a local server for development:

    $ php artisan serve

The install will be available to 127.0.0.1:8000

This is what I use on my main workstation (linux based) since it's very simple and very quick.

There are a lot of other options, check the laravel doc and ecosystem to have an overview of the options for local developement.



# Working on design and css


Install nodejs and npm (current version of nodejs is 10)

Then in the root of the project run:

    $ npm install

You will be able to have auto updated browser when you change a file by running:

    $ npm run watch


When you are done, run:

    $ npm run prod

To generate production ready css and js files.

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
