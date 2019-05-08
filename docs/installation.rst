Installation
============

Before installing Agorakit, if you just want to see if it fits your requirements, you might just create a free account on https://app.agorakit.org. This instance is free for citizen-activists and for evaluation purposes.

If you want managed hosting of a private Agorakit  instance, contact us at info [at] agorakit.org

On the other hand if you want to install it on your own server, here are the required steps :

Requirements
------------

You need a good web hosting provider that provides the following :

- php >= 7.1.3 with the following extensions :
    - OpenSSL PHP Extension
    - PDO PHP Extension
    - Mbstring PHP Extension
    - Tokenizer PHP Extension
    - XML PHP Extension
    - Ctype PHP Extension
    - JSON PHP Extension
    - BCMath PHP Extension
- Mysql or MariaDb
- Composer
- Git
- the ability to run cron jobs

All those features together are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal if you don't know how it works.

We have been very successful with https://www.alwaysdata.com shared hosting (By the way they host free of charge the free instance of Agorakit at https://app.agorakit.org).


Installation
------------

Currently, you need to know how to install a Laravel application using the command line.
This is perfectly standard and documented here : https://laravel.com/docs/master/installation.



Clone the repository::

  $ git clone https://github.com/philippejadin/agorakit.git


Create and edit the configuration file from the example provided::

  $ cp .env.example .env
  $ nano .env

You need to set at least your database credentials & site name. Check that your database exists and is reachable with those credentials.

Here is a description of every setting in the .env file::

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



Download all the packages needed::

  $ composer install`

Generate a key::

  $ php artisan key:generate`

Migrate (create all tables in) the database::

 $ php artisan migrate`

(Optional) Create sample content the database::

  $ php artisan db:seed`

Don't do this last step for a production install since it will create an admin user and dummy groups and content.

Setup your web server
---------------------
Then setup your web server to serve the /public directory. This is very important, since you don't want to expose the rest of the directories (for example you DON'T want to expose your .env file!)

Setup a cron job
----------------
Follow Laravel cron documentation here : https://laravel.com/docs/5.2/scheduling
The cron jobs are used to send group summaries at a fixed interval, for the inbound email handler and for various database interactions.


Setup inbound emails
--------------------
This additional step allows you to have one mailbox for each group so members can post by email.

You need an email address on server with imap. It must either be a catch all on a subdomain (or even on a domain) or a server supporting "+" addressing (gmail for example allows this).

Let's say you installed Agorakit on agora.example.org
Create a catchall mailbox on *.@agora.example.org

Then go to admin settings and fill the form there (end of the page).

You need to fill server & login & password

Then you need to fill prefix and suffix. Two cases there :


For a catch all there is no prefix. The suffix in the above example would be @agora.example.org . This will create emails like group-slug@agora.example.storing

On the other hand if you use "+" addressing (a gmail box for instance, let's call it agorakit@gmail.com),

- prefix will be agorakit+
- suffix will be @gmail.com

Wich create emails like agorakit+group-slug@gmail.com

If you enable inbound email, the mailbox will be automatically checked and processed email will be put in a  "processed" folder under INBOX. Failed emails will be similarly put a "Failed" folder under INBOX for inspection.
