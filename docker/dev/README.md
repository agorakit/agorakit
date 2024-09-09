This container setup can be used for developement.

It provides an as small as possible image that reflects a potential production environment using 

- frankenphp as a php runtime : agorakit_php
- mariadb : agorakit_database
- mailpit to access email sent : agorakit_mailpit
- phpmyadmin : agorakit_phpmyadmin


Steps to use : 
- Install docker compose
- Run 'docker compose up' in the current directory (docker/dev) to start the container
The container will take a while to build and if all goes well, you can access the app on localhost (either on http or https)

- Connect to the runing container's shell using the bash.sh script