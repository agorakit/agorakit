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
* Filters the passed text to remove nasty html and turns urls to html links and embeds youtube and vimeo links
* @param  [type] $content [description]
* @return [type]          [description]
*/
function filter($content)
{
    // strip bad stuff
    $content = safe_html($content);

    // convert links to embedable content TODO much more that that is needed
    // taken from http://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
    // and from http://stackoverflow.com/questions/28563706/how-to-convert-vimeo-url-to-embed-without-letting-go-of-the-text-around-it

    /*
    // 1. Youtube
    $content = preg_replace(
    "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
    "<div class=\"embed-responsive embed-responsive-16by9\">
    <iframe src=\"//www.youtube.com/embed/$2\" allowfullscreen frameborder=\"0\" class=\"embed-responsive-item\">
    </iframe></div>", $content);

    // 2. Vimeo
    $content = preg_replace('#https?://(www\.)?vimeo\.com/(\d+)#',
    '<div class="embed-responsive embed-responsive-16by9">
    <iframe class="videoFrame" src="//player.vimeo.com/video/$2" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen class="embed-responsive-item">
    </iframe>
    </div>',$content);
    */

    // add links and returns
    return linkUrlsInTrustedHtml($content);
}


function safe_html($content)
{
    return strip_tags($content, '<br><p><a><li><img><hr><em><strong><i><code><h1><h2><h3><h4><ul><ol>');
}



/**
* returns the value of $name setting as stored in DB // TODO refactor
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
