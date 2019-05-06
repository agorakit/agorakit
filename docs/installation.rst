Installation
============

Requirements
------------

You need a good webhosting provider that provides the following :

- php 7+
- mysql
- composer
- git
- the ability to run cron jobs

All those features together are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal. I have been very successful on https://www.alwaysdata.com hosting.


Installation
------------

Currently, you need to know how to install a Laravel application. This is perfectly standard and documented. You need composer up and running.

Clone the repository
********************

  $ git clone https://github.com/philippejadin/agorakit.git`


Create and edit the configuration file from the example provided
****************************************************************
  $ cp .env.example .env
  $ nano .env

You need to set at least your database credentials, site name. Check that your database exists and is reachable with those credentials.

Here is a description of every setting in the .env file :


  APP_ENV=local  // local or production
  APP_DEBUG=true // show the debugbar and extended errors or not
  APP_KEY=SomeRandomString // will be auto generated
  APP_NAME='Agorakit' // name of your application
  APP_URL=http://locahost // base url
  APP_LOG=daily // log rotation
  APP_DEFAULT_LOCALE=en // default locale when not detected from user browser

  DB_HOST=localhost // host of your mysql server
  DB_DATABASE=agorakit // db name of your sql DB
  DB_USERNAME=root // login of mysql
  DB_PASSWORD= // password of mysql

  CACHE_DRIVER=file // driver to use for caching
  SESSION_DRIVER=file // driver to use for storing sessions
  QUEUE_DRIVER=sync // driver to use for queues

  MAIL_DRIVER=smtp // driver to use for sending emails. Use mail to use php built-in mail function
  MAIL_HOST=mailtrap.io // hostname if you use smtp for sending mails
  MAIL_PORT=2525 // port if you use smtp for sending mails
  MAIL_USERNAME=null // login if you use smtp for sending mails
  MAIL_PASSWORD=null // password if you use smtp for sending mails
  MAIL_ENCRYPTION=null // encryption if you use smtp for sending mails

  MAIL_FROM=admin@localhost // from email adress used when sending admin emails
  MAIL_FROM_NAME=Agorakit // name of sender of admin emails
  MAIL_NOREPLY=noreply@localhost // no reply adress for service messages



Download all the packages needed
********************************
`$ composer install`

Generate a key
**************
`$ php artisan key:generate`

Migrate (create all tables in) the database
*******************************************
`$ php artisan migrate`

(Optional) Create sample content the database
*********************************************
`$ php artisan db:seed`

Setup your web server
*********************
Then setup your web server to serve the /public directory. This is very important, since you don't want to expose the rest of the directories (for example you DON'T want to expose your .env file!)

Setup a cron job
****************
Follow laravel cron documentation here : https://laravel.com/docs/5.2/scheduling
