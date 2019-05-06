## Provide external authentification to Agorakit

You can provide facebook, google, twitter and/or github authentication to your users.

This feature is a work in progress, help is highly welcome.

To do so, get a client id, a client secret from each provider you want to use. Those are a string of characters you need to put in your .env file

Agorakit will automatically put the links on the login and registration page for each provider when [PROVIDER]_ ID is set

- For Twitter go to : https://apps.twitter.com/app/new
- For Facebook, go to: https://developers.facebook.com/apps
- For Github go to https://github.com/settings/applications/new
- For Google go to : http://console.developers.google.com/

It's a bit of a pain to find where to get the client_id and client_key, so good luck :-)

You also need to set [PROVIDER]_ URL to http[s]://[yourdomain]/auth/[provider]/callback for each provider, in your .env file
