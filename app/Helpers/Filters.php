<?php

use Embera\Embera;

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
    // strip bad stuff using html purifier
    $content = Purifier::clean($content);

    // link to user mentions
    $content = highlightMentions($content);

    // link to f:xx files
    $content = highlightFiles($content);

    // link to d:xx discussions
    $content = highlightDiscussions($content);

    // embed youtube and others
    // we create an embera class with offline support to reduce load : https://github.com/mpratt/Embera#offline-support
    $config = [
        'fake_responses' => Embera::ONLY_FAKE_RESPONSES,
        'responsive' => true
    ];

    $embera = new Embera($config);
    $content = $embera->autoEmbed($content);

    // link to urls
    $linkify = new \Misd\Linkify\Linkify();
    $content = $linkify->process($content);

    // remove empty <p>
    //$content =  preg_replace("/<p[^>]*><\\/p[^>]*>/", '',  $content);

    return $content;
}

/**
 * Highlight and link to user profiles in the passed $content.
 *
 * Mentions may only include "word characters" (alphanumeric or underscore).
 * Assertions: "Start of line OR character directly before @ must be non-word AND NOT in the [listed set]."
 *   Because @ is a valid URL character, we must endeavor to not break links.
 *   Non-word valid URL characters: /~!?#@$&+='[]()*.,;:
 *   Skip '[]()*.,;: because they are more likely to directly precede @ in prose than a URL.
 * @see https://www.php.net/manual/en/regexp.reference.assertions.php
 *
 * @param string $content
 * @return ?string
 */
function highlightMentions(string $content): ?string
{
    $urlChars = '\/~!?#@$&+='; // Begins with escaped forward slash.
    if (hasUnicodeSupport()) {
        // Use unicode letters & numbers if it's supported (adding underscores and PCRE_UTF8 flag).
        $pattern = "/(?<=^|[\PL\PN_])(?<![$urlChars])@([\pL\pN_\-]+)/u";
    } else {
        // Fallback to "word" characters, which depend on server's locale.
        $pattern = "/(?<=^|\W)(?<![$urlChars])@([\w\-]+)/";
    }
    return preg_replace($pattern, '<a href="' . URL::to('/') . '/users/$1">@$1</a> ', $content);
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
                    return route('groups.files.show', [$file->group, $file]);
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
