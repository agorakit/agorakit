This container setup can be used for developement.

It provides an as small as possible image that reflects a potential production environment using 

- frankenphp as a php runtime : agorakit_php
- mariadb : agorakit_database
- mailpit to access email sent : agorakit_mailpit
- phpmyadmin : agorakit_phpmyadmin


## Setup dev environment

- Install docker compose
- Run 'docker compose up' in the current directory (docker/dev) to start the container
The container will take a while to build and if all goes well, you can access the app on localhost (either on http or https)


## Shell access
- Connect to the runing container's shell using the 'bash.sh' script

## Web access
The app can be reached on http://localhost or https://localhost (you need to let your browser accept the local self signed certificate with https)

## Phpmyadmin
Can be accessed on port 8080 : http://localhost:8080

## Read emails
Mailpit can be accessed on port 8025 : http://localhost:8025