# Installation

## Trying without installing 

If you are just looking to give Agorakit a try, you can do that without having to install it. Just create an account on <https://app.agorakit.org>, where you can try an Agorakit instance free for citizen-activists and for evaluation purposes.

You can also get in touch with us if you are interested in managed hosting of a private Agorakit instance. Email us at info [at] agorakit.org for more details.

Keep reading for steps to install an Agorakit instance on your own server.

## Requirements

You need a good web hosting provider that provides the following :

- php >= 8 with the following extensions :
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

!> All those features together are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal if you don't know how it works. We have been very successful with [Alwaysdata](https://www.alwaysdata.com) shared hosting. By the way they host at a reduced fee [the free instance of Agorakit](https://app.agorakit.org).


# Installation

Currently, you need to know how to install a Laravel application using the command line.
This is perfectly standard and documented here : https://laravel.com/docs/master/installation.



Clone the repository:

        $ git clone https://github.com/agorakit/agorakit


Create and edit the configuration file from the example provided:

        $ cp .env.example .env
        
        $ nano .env


!>You need to set at least your database credentials & site name. Check that your database exists and is reachable with those credentials.


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


Then configure you mail server. For the mail driver you can use `mail` to use php built in mail function or `smtp` to use any smtp server. Choose the right port (on most servers this is 25), host and username / password.

!>This is needed to be able to verify any registered user account. If you cannot verify your user account, you can always set the column `verified` to `1` inside the users table.


        MAIL_DRIVER=mail // driver to use for sending emails. Use mail to use php built-in mail function
        MAIL_HOST=mailtrap.io // hostname if you use smtp for sending mails
        MAIL_PORT=2525 // port if you use smtp for sending mails
        MAIL_USERNAME=null // login if you use smtp for sending mails
        MAIL_PASSWORD=null // password if you use smtp for sending mails
        MAIL_ENCRYPTION=null // encryption if you use smtp for sending mails

        MAIL_FROM=admin@localhost // from email adress used when sending admin emails
        MAIL_FROM_NAME=Agorakit // name of sender of admin emails
        MAIL_NOREPLY=noreply@localhost // no reply adress for service messages

        MAPBOX_TOKEN=null // Create a Mapbox account and generate a token to enable geolocalisation and display maps



Download all the packages needed:

        $ composer install

Generate a key:

        $ php artisan key:generate

Migrate (create all tables in) the database:

        $ php artisan migrate

 Link the storage public folder to the user visible public folder:

        $ php artisan storage:link

(Optional) Create sample content the database:

        $ php artisan db:seed

Don't do this last step for a production install since it will create an admin user and dummy groups and content.


## Setup your web server

Then setup your web server to serve the `/public` directory. This is very important, since you don't want to expose the rest of the directories (for example you DON'T want to expose your .env file!)


## Setup a cron job

A cron job is a task your server runs at specific intervals to run stuff in the background.

Agorakit needs to periodically do some tasks, like sending email summaries, celaning the DB, remove old versions of content, delete old files, etc...

The script `php artisan schedule:run` should be run at least every 5 minutes using a cron job.

Follow Laravel cron documentation here : https://laravel.com/docs/master/scheduling


!> Without cron job your application will **NOT** send summaries. Cron jobs are required for correct opperation.

