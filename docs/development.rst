Developing with Agorakit
========================

You want to contribute to the project? Great!

First follow the installation instructions including the creation of sample content using ::

  $ php artisan db:seed




Option 1 : Using php's built in server
--------------------------------------


If you want to start a local server for development::

  $ php artisan serve

The install will be available to 127.0.0.1:8000



Option 2 : Using Vagrant, for development purposes
--------------------------------------------------

To use vagrant, you need to install : [Virtual Box](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](https://www.virtualbox.org/wiki/Downloads).

After in the root of your project::

  $ vagrant up
  $ vagrant ssh
  $ cd agorakit
  $ composer install
  $ php artisan key:generate
  $ php artisan migrate
  $ php artisan db:seed (if you want the db filled with fake infos)


Then you will have access to the project using :

http://192.168.10.12/

Modifying your hosts file

You must add the "domains" for your Nginx sites to the hosts file on your machine. The hosts file will redirect requests for your Homestead sites into your Homestead machine. On Mac and Linux, this file is located at /etc/hosts. On Windows, it is located at C:\Windows\System32\drivers\etc\hosts.


Working on design and css
=========================

Install nodejs and npm (current version of nodejs is 10)

Then in the root of the project run::
  $ npm install

You will be able to have auto updated browser when you change a file by running::
  $ npm run watch


When you are done, run::
  $ npm run prod

To generate production ready css and js files.


Testing your code
=================

Agorakit is tested using the Laravel testing framework.

In order to test, you need to have an existing testing database. Just create an additional empty DB, for instance agorakit_testing and check in the phpunit.xml file that everything matches.

Before comiting code, you should either write more tests (in this case you deserve a cookie). Or at least check that you didn't break anything by simply typing::

  ./vendor/phpunit/phpunit/phpunit

...in the root of your project.

No error should appear (provided that you have everything correctly set up.

We use travis ci to run all those tests on commit so it will be done automatically for you at some point :-)

Writing tests
=============
Don't hesitate to write tests. We favor well defined tasks an end user would really accomplish, like registering, creating an account, posting, uploading, etc... It has served us very well in the past to spot errors and it really mirrors real use cases. Although we are open to other kind of tests as well...
