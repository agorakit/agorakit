[![Build Status](https://travis-ci.org/philippejadin/Mobilizator.svg?branch=master)](https://travis-ci.org/philippejadin/Mobilizator)


## AgoraKit, a groupware for citizen

AgoraKit is a web based open source groupware for citizens initiatives. By creating collaborative groups, people can discuss, organize events, store files and keep everyone updated when needed. AgoraKit is a forum, agenda, file manager and email notifier. 

Manage communication, decision making, membership, files and events. Flexible email notifications per group, per user preferences. Most of the time no admin is involved in the process as we try to keep it as horizontal as possible. 

In other words : an organized Facebook for the paranoid inside any of us.


### Create groups

!(https://agorakit.org/mobilizator_groups.jpg)

You can create as many groups as you like, groups can be fully open or closed (membership approval required).


### Manage a collaborative agenda

!(https://agorakit.org/mobilizator_agenda.jpg)

Each group has an agenda, you can display a complete agenda of every groups. iCal feed for each agenda ready to be imported elsewhere.


### Geolocalize groups, people and events

!(https://agorakit.org/mobilizator_map.jpg)

Put everything on a nice map automatically. Map everyone, every group and every event as needed.


### Get an overview of your unread discussions and upcoming events

!(https://agorakit.org/mobilizator_overview.jpg)

Every user get a dashboard where you can see every unread discussions. No more mailing list horror.


### Receive email notifications at the rate YOU specify for each groups
aka "I don't want to be spammed for each comment in each group"

!(https://agorakit.org/mobilizator_notifications.jpg)

Everyone can decide how often to receive notifications, for each groups. Choose your level of involvment per group. Also known as "do not disturb me more than once a week"



## Requirements
You need a good webhosting provider that provides the following :
- php 5.6 or newer (7+ recomended)
- mysql
- composer
- git
- the ability to run cron jobs

All those features together are are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal. I successfuly installed everything at alwaysdata.com and combell.com (minus the cron job feature in the case of combell.com, which means you won't have automated email notifications)


## Installation

Currently, you need to know how to install a laravel application. This is perfectly standard and documented. You need composer up and running.

In short :

```
$ git clone https://github.com/philippejadin/agorakit.git
$ cp .env.example .env
$ editor .env
$ composer install
$ php artisan key:generate
$ php artisan migrate
```
- Then setup your webserver to serve the /public directory.

Check the wiki for more informations


## Usage
- Open your browser and go to http://localhost:8000
- Register a first user account : it will be you, and you will be super admin, yeah!
- Create one or more groups
- Set an intro text on the homepage for new comers
- Invite people to one or more groups using the invite feature of each group
- Profit (or in some cases, revolution!)


## Detailed installation, development, usage, contributing

Check the wiki


## Contributing : Laravel backend & frontend developer wanted
We are looking for help to enhance this collaborative platform called "Mobilizator" used by multiple citizen initiatives (Tout autre chose, Hart Boven Hard, Collectif Roosevelt, Stop-TTIP, etc.).

- Backend developer with experience of the Laravel framework : add new features to the existing tool, fix bug, write tests, document everything
- Frontend developer with experience of the Laravel framework : the current UI is not pretty, make it brilliant. Fix UX as you see fit.

If you have some time to give to this project, there are already some issues to fix (see. https://github.com/philippejadin/Mobilizator/issues ). Whether you have one hour per day or one hour per month, help is really appreciated! And remember, "talk is silver, code is gold" :-)

All the work is open source (GPL) and will benefit every interested citizen initiative. We already have 100's of users who might benefit from your help.


## Security Vulnerabilities

Contact me

## License

This tool is released under the GPL 3 licence
