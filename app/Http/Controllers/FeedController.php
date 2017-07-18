<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;
use Config;

class FeedController extends Controller
{
    public function discussions()
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
        ->title(Config::get('agorakit.name') . ' : ' . trans('messages.latest_discussions'))
        ->description(Config::get('agorakit.name'))
        ->ttl(60)
        ->appendTo($feed);

        $discussions = \App\Discussion::with('group')->with('user')->orderBy('created_at', 'desc')->take(20)->get();

        foreach ($discussions as $discussion)
        {
            $item = new \Suin\RSSWriter\Item();
            $item
            ->title($discussion->name)
            ->description($discussion->body)
            ->contentEncoded($discussion->body)
            ->url(action('DiscussionController@show', [$discussion->group, $discussion]))
            ->author($discussion->user->name)
            ->pubDate($discussion->created_at->timestamp)
            ->guid(action('DiscussionController@show', [$discussion->group, $discussion]), true)
            ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
            ->appendTo($channel);
        }

        return $feed;
    }


    public function actions()
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
        ->title(Config::get('agorakit.name') . ' : ' . trans('messages.agenda'))
        ->description(Config::get('agorakit.name'))
        ->ttl(60)
        ->appendTo($feed);

        $actions = \App\Action::with('group')->with('user')->orderBy('start', 'desc')->take(20)->get();

        foreach ($actions as $action)
        {
            $item = new \Suin\RSSWriter\Item();
            $item
            ->title($action->name)
            ->description($action->body)
            ->contentEncoded($action->body)
            ->url(action('ActionController@show', [$action->group, $action]))
            ->author($action->user->name)
            ->pubDate($action->start->timestamp)
            ->guid(action('ActionController@show', [$action->group, $action]), true)
            ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
            ->appendTo($channel);
        }

        return $feed;
    }

}
