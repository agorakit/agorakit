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

