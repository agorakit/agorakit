<?php

/**
* Returns a summary of thep rovided text
* @param  [type]  $text   $text to be summarized
* @param  integer $length lenght in chars to keep
* @return [type]          summarized text
*/
function summary($text, $length = 200)
{

    if (strlen(str_limit(strip_tags($text))) > $length)
    {
        $post = ' ...'; // append if it's longer than length
    }
    else
    {
    $post = '';
    }
    return str_limit(strip_tags($text), $length) . $post;
}

/**
* Filters the passed text to remove nasty html and turns urls to html links
* @param  [type] $content [description]
* @return [type]          [description]
*/
function filter($content)
{
    /*

    $content = preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" target="_blank">$2</a> ', $content." ");
    $content = preg_replace('$(\s|^)(www\.[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a target="_blank" href="http://$2"  target="_blank">$2</a> ', $content." ");
    */

    return safe_html(linkUrlsInTrustedHtml($content));
}


function safe_html($content)
{
    return strip_tags($content, '<br><p><a><li><img><hr><em><strong><i><code><h1><h2><h3><h4><ul><ol>');
}

/**
* returns the value of $name setting as stored in DB
*/
function setting($name, $default = false)
{
    $setting = \App\Setting::where('name', $name)->first();

    if ($setting)
    {
        return $setting->value;
    }

    return $default;
}
