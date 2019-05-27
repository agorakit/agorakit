<?php

// TODO refactor some of this using a content filter helper class or something

/**
* Returns a summary of the provided text.
*
* @param [type] $text   $text to be summarized
* @param int    $length lenght in chars to keep
*
* @return [type] summarized text
*/
function summary($text, $length = 200)
{
    return mb_strimwidth(strip_tags(html_entity_decode($text, ENT_QUOTES, 'utf-8')), 0, $length, '...');
}

/**
* Filters the passed text to remove nasty html and turns urls to html links and embeds youtube and vimeo links.
*
*/
function filter($content)
{
    // strip bad stuff
    $content = safe_html($content);

    // link to user mentions
    $content = highlightMentions($content);

    // link to f:xx files
    $content = highlightFiles($content);

    // link to d:xx discussions
    $content = highlightDiscussions($content);

    // link to urls
    $content = linkUrlsInTrustedHtml($content);

    return $content;
}

function safe_html($content)
{
    return strip_tags($content, '<br><p><a><li><img><hr><em><strong><i><code><h1><h2><h3><h4><ul><ol>');
}

/**
* Highlight and link to @user profiles in the passed $content.
*/
function highlightMentions($content)
{
    return preg_replace("/(?<!\w)@([\w_\-\.]+)/", '<a href="/users/$1">@$1</a> ', $content);
}

/**
* Highlight and link to f:xx files and d:xx discussions
*/
function highlightFiles($content)
{
    return preg_replace_callback(
        '/f:([0-9]+)/', function ($matches)
        {
            $file = \App\File::find($matches[1]);
            if ($file){
                return view('files.embed')
                ->with('file', $file)
                ->render();
            }
            else {
                return $matches[0];
            }
        }, $content
    );
}


/**
* Highlight and link to f:xx files and d:xx discussions
*/
function highlightDiscussions($content)
{
    return preg_replace_callback(
        '/d:([0-9]+)/', function ($matches)
        {
            $discussion = \App\Discussion::find($matches[1]);
            if ($discussion){
                return view('discussions.embed')
                ->with('discussion', $discussion)
                ->render();
            }
            else {
                return $matches[0];
            }
        }, $content
    );
}


/**
* returns the value of $name setting as stored in DB.
*/
function setting($name, $default = false)
{
    return \App\Setting::get($name, $default);
}

function sizeForHumans($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).'GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).'MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).'KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function intervalToMinutes($interval)
{
    $minutes = 60 * 24;

    switch ($interval) {
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

// this one line replace almost all laracast flash tutorial that became bloated for our use case
function flash($message)
{
    session()->push('messages', $message);
}
