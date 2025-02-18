<?php

use Carbon\Carbon;

/**
 * General helper functions that don't have a batter place.
 */

/**
 * returns the value of $name setting as stored in DB.
 */
function setting($name = false, $default = false)
{
    $setting = new \App\Setting;
    if ($name) {
        return $setting->get($name, $default);
    }
    return $setting;
}


function sizeForHumans($bytes)
{
    if ($bytes >= 1000000000) {
        $bytes = number_format($bytes / 1000000000, 1) . 'GB';
    } elseif ($bytes >= 1000000) {
        $bytes = number_format($bytes / 1000000, 1) . 'MB';
    } elseif ($bytes >= 1000) {
        $bytes = number_format($bytes / 1000, 0) . 'KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}


/** 
 * Format a date correctly, if less than 2 days, use carbon diffForhumans, else returns a formated date
 */
function dateForHumans($date)
{
    if ($date->lessThan(Carbon::now()->subHours(12))) {
        return $date->isoFormat('LLL'); // soooo much better like that cfr. https://carbon.nesbot.com/docs/#api-localization
    }
    return $date->isoFormat('H:mm');
    //return $date->diffForHumans();
}

function intervalToMinutes($interval)
{
    $minutes = 60 * 24;

    switch ($interval) {
        case 'instantly':
            $minutes = 1;
            break;
        case 'hourly':
            $minutes = 60;
            break;
        case 'daily':
            $minutes = 60 * 24;
            break;
        case 'weekly':
            $minutes = 60 * 24 * 7;
            break;
        case 'biweekly':
            $minutes = 60 * 24 * 14;
            break;
        case 'monthly':
            $minutes = 60 * 24 * 30;
            break;
        case 'never':
            $minutes = -1;
            break;
    }

    return $minutes;
}

function minutesToInterval($minutes)
{
    $interval = 'daily';

    switch ($minutes) {
        case 1:
            $interval = 'instantly';
            break;
        case 60:
            $interval = 'hourly';
            break;
        case 60 * 24:
            $interval = 'daily';
            break;
        case 60 * 24 * 7:
            $interval = 'weekly';
            break;
        case 60 * 24 * 14:
            $interval = 'biweekly';
            break;
        case 60 * 24 * 30:
            $interval = 'monthly';
            break;
        case -1:
            $interval = 'never';
            break;
    }

    return $interval;
}


function flash($message)
{
    session()->push('messages', $message);
}


function error($message)
{
    session()->push('agorakit_errors', $message);
}


function warning($message)
{
    session()->push('warnings', $message);
}

// Geocode function - even more abstracted than geocoder php.
// Pass it a string and it will return an array with longitude and latitude or false in case of problem
function geocode($location)
{
    $geocode = app('geocoder')->geocode($location)->get()->first();

    if ($geocode) {
        $result['latitude'] = $geocode->getCoordinates()->getLatitude();
        $result['longitude'] = $geocode->getCoordinates()->getLongitude();

        return $result;
    }

    return false;
}



/**
 * provide a link to any model
 */
function linkTo($model)
{
    if ($model instanceof App\User) {
        return route('users.show', $model);
    }

    if ($model instanceof App\Discussion) {
        return route('groups.discussions.show', [$model->group, $model]);
    }

    if ($model instanceof App\File) {
        return route('groups.files.show', [$model->group, $model]);
    }

    if ($model instanceof App\Action) {
        return route('groups.actions.show', [$model->group, $model]);
    }

    if ($model instanceof App\Comment) {
        return route('groups.discussions.show', [$model->group, $model->discussion]);
    }
}
