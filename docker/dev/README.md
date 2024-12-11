This container setup can be used for developement.

It provides an as small as possible image that reflects a potential production environment using 

- Frankenphp as a php runtime : container name is **agorakit_php**
- Mariadb : container name is **agorakit_database**
- Mailpit to access email sent : container name is **agorakit_mailpit**
- Phpmyadmin : container name is **agorakit_phpmyadmin**


## Setup dev environment
- Install docker and docker compose

- In order to avoid permission issues, you can define GID and UID environment variables via the `setuid.sh` script. This scripts simply set UID and GID to your current user id (UID) and group id (GID). This will be the user owning the files written by the application. This is to avoid having files owned by root. More explanations can be found here : https://aschmelyun.com/blog/fixing-permissions-issues-with-docker-compose-and-php/

- Run `up.sh` or `docker compose up` in the current directory (docker/dev) to start the container.

The container will take a while to build and if all goes well, you can access the app on localhost (either on http or https)

- Connect to the shell inside the container using `./bash.sh`
- `cp .env.dev .env` _(Hint: before you rebuild, make sure to push this .env out of the way)_
- `composer install`
- `php artisan key:generate`
- `php artisan migrate`

With this you have a working dev setup!

!! Don't use this for production, there are probably security issues that need to be adressed/hardened for production.



You can now access your installation with the following : 


## Shell access
Connect to the runing container's shell using the `bash.sh` script. You will land directly in the root of the app and can run php artisan commands or composer for example.

## Web access
The app can be reached on http://localhost or https://localhost (you need to let your browser accept the local self signed certificate with https)

## Phpmyadmin
Can be accessed on port 8080 : http://localhost:8080

## Read emails
Mailpit can be accessed on port 8025 : http://localhost:8025

(Mailpit API documentation is available here : http://localhost:8025/api/v1)
