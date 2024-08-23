# Agorakit 2.0 (very soon)
- Major bug fixed
- Easy Docker based install
- Polished UI also on mobile
- Enhance usability and accessibility (need help)
- More documentation (need help)
- More locales (need help)

# Agorakit 3.0 (Q4 2024 - Q1 2025)
- Better integration (Embed, Ical, RSS) of all content including public calendar of events
- Web push notifications + UI to manage those per group / per user
- Bridge with collabora online / only office (WOPI) to allow online editing of docs
- Webdav access for the files if the above does not work OR integrate nextcould or similar file storage backend for groups
- Fediverse integration (minimal feature : allow Fediverse users to follow groups)

# Future of the project
- More complete Fediverse integration if there is demand
- Enhance the calendar features
- Allow groups to federate cross servers cross applications
- Your idea could be here :)


# Planed work
The following items have been proposed for NGI funding : 

## Documentation
- Provide more complete end user documentation including visuals if needed.
- Provide better group admin documentation.
- The goal is to cover at least all the user facing features of Agorakit in the docs.

## Simplify hosting
Attracting new developers and server administrators requires a simple, standardized installation workflow.
- Docker/OCI containers and (docker-)compose are a suitable approach for that:
    - provide a production compose setup
    - provide a development compose setup if needed (currently using laravel sail which works fine but seems overkill)

- document the process & alternatives (for example, I’d like to test if sqlite is an alternative for small instances, if it works, I’d provide a separate docker compose setup for this use case)

## External members
Allow people outside a closed group to contact the group. Either by web form, email or using a fediverse account (real inbox for groups).
- Allow a group to publish public content (currently ical and rss are supported), provide a fediverse outbox at least for public content (public events including those in private groups
are a good candidate)
- This means extending users model & database to allow external users to be stored using their fediverse handle instead of email. This also means adding a new “external” role to
group members. Finally it would be nice to have users authenticate using their fediverse account to reduce friction shall they want to join a group.
- The way an external request (from someone outside a group) would be handled could be inspired by Basecamp external client feature. Basically, when an “external” person contacts a group, a new discussion is created, labeled as “from an external person”, the group can discuss as usual, and the whole discussion is sent to the external person as well.

## Federation
Allow groups to communicate with the Fediverse. Paves the way for a more complete Fediverse integration.
- Search, test, choose, add an activitypub library
- Outbox for groups containing public events
- Auth & external contact from activitypub

## Mobile
Agorakit is already a progressive web app that can be “installed” on mobile and desktop. Roughly 40% of users are on small screen, the UI need to be enhanced. Users expect to receive
notifications in real-time with a mobile application like Agorakit.
- Native mobile notifications using web push notifications + user interface to configure those
(same way as emails notification preference UI).
- Check all pages on mobile and fix readability / layout


# Additional ideas 
Those would be good to implement at some point but are not yet prioritized. Let's collect here all the (wild or not) ideas.

## Discussions
- Store attachments in a separate directory in files, called "discussions" for example
- Fix the "scroll to latest unread item" functionality on discussion list
- Provide a nice navigation for longer discussions (check Flarum & Discourse implementations)... but do it in a simple way.
- Improve image display in text content : https://feedback.agorakit.org/posts/10/improve-image-display
    "When you upload an image, it is displayed in the same form as a file of another type (pdf or other).
    Would it be possible (when they are jpg, gif, png) to have a more "nice" display ;0) --> namely the image which is displayed directly.
    And this with the possibility of enlargement, with a lightbox effect?"

## Files
- Provide a Webdav access to files, per group. Also for connected users, show all their groups, each in a separate folder when using webdav.
- Provide a WOPI interface to allow integration with online office tools (collabora, onlyoffice and office seems to use this not very known standard)
- Allow to drop a file anywhere on the window browser to upload
- Preview more file types on file embed and show view.

## Notifications
- More notifications with UI to manage if you get one and on which channel. For example "get an email / webpush when an event is created in group xyz". "When someone joins / leaves / etc. the group xyz". 
- Per group, per user. 
- Allow those notifications to be grouped in the summary email as well. 
- Provide a nice UI with nice defaults and simple checkboxes. 
- Additional notifications types for group admins.

## Installation
Use Phing based build to provide an archive users just have to upload in order to install Agorakit on shared hosting.

