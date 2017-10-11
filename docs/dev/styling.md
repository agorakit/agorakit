# How css styling works in agorakit?

We use bootstrap (currently 3.x) and laravel mix. Everything happens in resources/assets/sass

- app.scss imports bootstrap, our variables and our custom css
- _variables is where you can change bootstrap vars (check the list here : https://getbootstrap.com/docs/3.3/customize/)
- agorakit.scss contains our additions to bootstrap
