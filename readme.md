[![Build Status](https://travis-ci.org/philippejadin/agorakit.svg?branch=master)](https://travis-ci.org/philippejadin/agorakit)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fphilippejadin%2Fagorakit.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fphilippejadin%2Fagorakit?ref=badge_shield)

![Agorakit, groupware for citizens](https://raw.githubusercontent.com/philippejadin/agorakit-website/master/agorakit-banner.png)

## Agorakit, groupware for citizens

AgoraKit is web-based, open-source groupware for citizens' initiatives. By creating collaborative groups, people can discuss topics, organize events, store files and keep everyone updated as needed. AgoraKit is a forum, calendar, file manager and email notifier.

Manage communication, decision making, membership, files and events. Flexible email notifications per group, per user preferences. Most of the time, no admin is involved in the process as we try to keep it as horizontal as possible.

In other words: an organized Facebook for the paranoid inside all of us.

Check https://www.agorakit.org for a more colorful overview.

## Features

### Create groups
- Create an unlimited amount of groups
- The group can be open (anyone can join) or private (invite only)
- The group can have one or more admins
- Each group has a discussion area, a calendar, a file repository, a member list & map.
- Only members can use the discussion area, calendar and files
- Content is public in public groups, and private in private groups (simple security model everyone understands)

### Discussions
- Create discussion topics
- Reply to topics with comments
- Mention others in comments using @name (they get notified)
- Mention files using f: (autocomplete opens)
- Mention other discussions using d: (autocomplete opens)

### Calendar
- Create events
- List upcoming events as a list or as a dynamic calendar
- Show geolocalized events on a map
- Global and per group calendar
- iCal feed for each calendar
- RSS
- Embed elsewhere using iframes

### Files
- Upload & tag several files at once
- Quick search among files by author, filename and tags
- Preview images / download
- re-tag files
- Mention files in comments

### Members
- Access a list of members (global / per group)
- Contact others without leaking your/their email (privacy)
- Check what others are up to (activity feed)
- Fill your profile with portrait, bio, address (if you want)

### Notifications / emails
- For each group, choose how often you want to be notified (every hour for the hardcore, everyday to keep your mailbox cool, every week or every month)
- Auto login to your account from "Reply" links inside the notification emails you receive (great time saving)
- Get instant notifications when someone mentions you (for urgent matters)

### Admin
- Get stats on everything
- Mass invite members using their email
- Mass add existing members to groups
- Settings control panel (in progress)

### Architecture
- Standard Laravel structured application. If you know Laravel, you can work with Agorakit easily
- Simple structure, no single page app complexities
- Bootstrap based UI
- Simple DB schema
- Simple file storage scheme (per group, per file id)

### Privacy
- Host it where you want
- Your data is yours
- No leakage of emails
- Geolocalization of users (on a voluntary basis) is randomized by ~100 meters
- Open source you can study and trust



## Requirements & installation
Check the wiki for more information : https://github.com/philippejadin/agorakit/wiki/Installation



## Contributing
It's all explained here :  https://github.com/philippejadin/agorakit/wiki/How-can-I-contribute-to-agorakit-%3F

> You are really welcome to help on this project !


## Security Vulnerabilities
Please drop a line to info (at) agorakit.org . Security issues will be dealt with care and speed.

## Contact
Please drop a line to info (at) agorakit.org to keep in touch.


## License
This tool is released under the GPL 3 licence


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fphilippejadin%2Fagorakit.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fphilippejadin%2Fagorakit?ref=badge_large)

## Credits
Idea & development : Philippe Jadin
Contributors : https://github.com/philippejadin/agorakit/graphs/contributors
Logo : Patrick Iacono
Illustrations : Raphaëlle Goffaux