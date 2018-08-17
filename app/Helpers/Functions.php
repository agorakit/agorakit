<?php

/**
* Returns a summary of thep rovided text.
*
* @param [type] $text   $text to be summarized
* @param int    $length lenght in chars to keep
*
* @return [type] summarized text
*/
function summary($text, $length = 200)
{
    if (strlen(str_limit(strip_tags($text))) > $length) {
        $post = ' ...'; // append if it's longer than length
    } else {
        $post = '';
    }

    return str_limit(strip_tags($text), $length).$post;
}

/**
* Filters the passed text to remove nasty html and turns urls to html links and embeds youtube and vimeo links.
*
* @param [type] $content [description]
*
* @return [type] [description]
*/
function filter($content)
{
    // strip bad stuff
    $content = safe_html($content);

    // add links and returns
    return linkUrlsInTrustedHtml($content);
}

function safe_html($content)
{
    return strip_tags($content, '<br><p><a><li><img><hr><em><strong><i><code><h1><h2><h3><h4><ul><ol>');
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
        $bytes = number_format($bytes / 1073741824, 2) . 'GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . 'MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . 'KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
