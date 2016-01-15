<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', action('DashboardController@index') );
});


// Home > Group
Breadcrumbs::register('group', function($breadcrumbs, $group)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($group->name, action('GroupController@show', [$group->id]));
});
