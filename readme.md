[![Build Status](https://travis-ci.org/philippejadin/agorakit.svg?branch=master)](https://travis-ci.org/philippejadin/agorakit)


## AgoraKit, groupware for citizens

AgoraKit is web-based, open-source groupware for citizens' initiatives. By creating collaborative groups, people can discuss topics, organize events, store files and keep everyone updated as needed. AgoraKit is a forum, calendar, file manager and email notifier.

Manage communication, decision making, membership, files and events. Flexible email notifications per group, per user preferences. Most of the time, no admin is involved in the process as we try to keep it as horizontal as possible.

In other words: an organized Facebook for the paranoid inside all of us.


### Create groups

![AgoraKit screenshot](http://agorakit.org/agorakit_groups.jpg)

You can create as many groups as you like, groups can be fully open or closed (membership approval required).


### Manage a collaborative calendar

![AgoraKit screenshot](http://agorakit.org/agorakit_agenda.jpg)

Each group has a calendar; you can display a complete calendar for every group. There is an iCal feed available for each calendar ready to be imported elsewhere.


### Geolocalize groups, people and events

![AgoraKit screenshot](http://agorakit.org/agorakit_map.jpg)

Put everything on a nice map automatically. Map everyone, every group and every event as needed.


### Get an overview of your unread discussions and upcoming events

![AgoraKit screenshot](http://agorakit.org/agorakit_overview.jpg)

Every user get a dashboard where you can see all unread discussions. No more mailing list horror.


### Receive email notifications at the rate YOU specify for each groups
aka "I don't want to be spammed for every comment in every group"

![AgoraKit screenshot](http://agorakit.org/agorakit_notifications.jpg)

Everyone can decide for themselves how often to receive notifications, for each group. Choose your level of involvement per group. Also known as "do not disturb me more than once a week".



## Requirements
You need a good web hosting provider that provides the following:
- PHP 5.6 or newer (7+ recomended)
- MySQL
- Composer
- git
- the ability to run cron jobs

All those features together are hard to find, so people are obliged to use a VPS and setup everything themselves. This is a riskier proposal. I successfuly installed everything at alwaysdata.com and combell.com (minus the cron job feature in the case of combell.com, which means you won't have automated email notifications)


## Installation

Currently, you need to know how to install a Laravel application. This is perfectly standard and documented. You need composer up and running.

In short:

```
$ git clone https://github.com/philippejadin/agorakit.git
$ cp .env.example .env
$ editor .env
$ composer install
$ php artisan key:generate
$ php artisan migrate
```
- Then setup your webserver to serve the /public directory.

Check the wiki for more information


## Usage
- Open your browser and go to http://localhost:8000
- Register the first user account: it will be you, and you will be super admin, yeah!
- Create one or more groups
- Set an intro text on the homepage for newcomers
- Invite people to one or more groups using the invite feature of each group
- Profit (or in some cases, revolution!)


## Detailed installation, development, usage, contributing

Check the wiki


## Contributing: Laravel backend & frontend developer wanted
We are looking for help to enhance this collaborative platform called "Agorakit" used by multiple citizen initiatives (Tout autre chose, Hart Boven Hard, Collectif Roosevelt, Stop-TTIP, etc.).

- Backend developer with experience of the Laravel framework: add new features to the existing tool, fix bug, write tests, document everything
- Frontend developer with experience of the Laravel framework: the current UI is not pretty, make it brilliant. Fix UX as you see fit.

If you have some time to give to this project, there are already some issues to fix (see. https://github.com/philippejadin/Agorakit/issues ). Whether you have one hour per day or one hour per month, help is really appreciated! And remember, "talk is silver, code is gold" :-)

All the work is open source (GPL) and will benefit every interested citizen initiative. We already have 100's of users who might benefit from your help.

Please drop a line to info (at) agorakit.org if you are interested.


## Security Vulnerabilities

Contact me

## Contact
Please drop a line to info (at) agorakit.org to keep in touch.



## License

This tool is released under the GPL 3 licence
