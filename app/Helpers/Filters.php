<?php

/**
* Text/html processing and filters
* The most used ont is filter($content).
*
* TODO : Refactor this so that is is part of the models ?
* TODO : caching of all those embeded files and discussions queries
*/

/**
* Returns a text only summary of the provided text.
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

    // embed youtube and others
    // we create an embera class with offline support to reduce load : https://github.com/mpratt/Embera#offline-support
    $config = [
        /*'oembed' => false,*/
        'responsive' => true
    ];

    $embera = new \Embera\Embera($config);
    $content = $embera->autoEmbed($content);

    // link to urls
    $linkify = new \Misd\Linkify\Linkify();
    $content = $linkify->process($content);

    return $content;
}


/**
* Keep only html tags considered "safe"
*/
function safe_html($content)
{
    return strip_tags($content, '<br><p><a><li><img><hr><em><strong><i><code><h1><h2><h3><h4><ul><ol><table><tr><td><th>');
}

/**
* Highlight and link to @user profiles in the passed $content.
*/
function highlightMentions($content)
{
    return preg_replace("/(?<!\w)@([\w_\-\.]+)/", '<a href="'.URL::to('/').'/users/$1">@$1</a> ', $content);
}

/**
* Highlight and link to f:xx files and d:xx discussions.
*/
function highlightFiles($content)
{
    return preg_replace_callback(
        '/f:([0-9]+)/',
        function ($matches) {
            $file = \App\File::find($matches[1]);
            if ($file) {
                if (Gate::allows('view', $file)) {
                    return view('files.embed')
                    ->with('file', $file)
                    ->render();
                } else {
                    return route('groups.files.show', [$file->group, $file]);;
                }
            } else {
                return $matches[0];
            }
        },
        $content
    );
}

/**
* Highlight and link to f:xx files and d:xx discussions.
*/
function highlightDiscussions($content)
{
    return preg_replace_callback(
        '/d:([0-9]+)/',
        function ($matches) {
            $discussion = \App\Discussion::find($matches[1]);

            if ($discussion) {
                if (Gate::allows('view', $discussion)) {
                    return view('discussions.embed')
                    ->with('discussion', $discussion)
                    ->render();
                } else {
                    return 'This content is unavailable';
                }
            } else {
                return $matches[0];
            }
        },
        $content
    );
}
