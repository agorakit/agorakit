# AgoraKit Roadmap
_Collected goals for the project._

Contribute an idea for discussion or propose a priority change by making a pull request!

Consider refining large projects into smaller ones before moving them higher in the list. Think about long term goals, then pick a good first iteration to prioritize that can be completed more easily. Momentum is more important than completeness. It isn't valuable until it ships.

¹ _Proposed for NGI funding_

## Planned
_Ordered list of prioritized projects. Everything in this section should have clear outcomes defined._

### Simplify hosting and development with Docker¹
_Attracting new developers and server administrators requires a simple, standardized installation workflow._
- Docker/OCI containers and (docker-)compose are a suitable approach for that:
    - Provide a production `compose setup`
    - Provide a development `compose setup` (replacing Laravel Sail)
- Document the process
- Document alternatives (e.g. a separate Docker for sqlite)

### Improve documentation¹
- End user documentation is nonexistent
- Group admin documentation needs improvement

### Allow external members¹
_Allow people outside a closed group to contact the group. Either by web form, email or using a fediverse account (real inbox for groups)._
- Allow a group to publish public content (currently iCal and RSS are supported)
    - Provide a fediverse outbox at least for public content (public events including those in private groups are a good candidate)
- Extend users model & database
    - Allow external users to be stored using their fediverse handle instead of email
    - Add new “external” role to group members
    - Nice to have: Users authenticate with fediverse account
- External requests (from outside a group)
    - When an “external” person contacts a group, a new discussion is created and labeled as “from an external person”
    - Group can discuss as usual and the whole discussion is sent to the external person as well
    - Example: Basecamp external client feature

### Add initial Fediverse access¹
_Allow groups to communicate with the Fediverse. First step toward further integration._
- Select an ActivityPub library
- Auth & external contact from ActivityPub
- Allow Fediverse users to follow groups
    - Outbox for groups containing public events

### Improve mobile experience¹
_AgoraKit is a progressive web app (PWA) with 40% of users on small screen. Users expect to receive
real-time notifications._
- Native notifications using web push notifications + user interface to configure those per group and per user
(same as email notification preferences UI)
- Audit all pages on mobile for usability and identify the biggestst challenges for remediation

### Improve accessibility
- Audit for WCAG 2.0 AA status as a first-pass and remediate as needed

### Improve content integration
- Support embed, iCal, RSS

### Improve localizaiton
- Review current localization system for potential improvements & automation
- Identify which new locales should be prioritized

### Enhance calendar
- Public calendar of events

-----

## Ideas 
_Not yet prioritized. Order doesn't matter. Let's collect all the ideas (wild or not) here._

### Discussions
- Store attachments in a separate directory in files, called "discussions" for example
- Fix the "scroll to latest unread item" functionality on discussion list
- Provide a nice navigation for longer discussions (ex: Flarum & Discourse) in a simple way
- Add [inline image display](https://feedback.agorakit.org/posts/10/improve-image-display)

### Files
- Provide Webdav access to files per group.
    - For connected users, show all their groups, each in a separate folder when using webdav
- Provide WOPI interface to allow integration with online office tools 
    - Collabora Online, OnlyOffice, and Office seems to use this not very known standard
- Allow drag-and-drop file upload
- Preview more file types on file embed and show view

### Notifications
- Add notifications
    - Enable/disable per channel
    - Per group, per user
    - Nice defaults and simple checkboxes
    - Examples:
        - "get an email / webpush when an event is created in group xyz"
        - "When someone joins / leaves / etc. the group xyz"
- Allow notifications to be grouped in the summary email
- Add notification types for group admins

### Simple install
- Use Phing-based build to provide a file archive users can upload directly to install AgoraKit on shared hosting

