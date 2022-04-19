<?php

namespace App\Http\Controllers;

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;

/**
 * This controller generates global public rss feeds for discussions and actions.
 */
class FeedController extends Controller
{
    public function discussions()
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
        ->title(setting('name').' : '.trans('messages.latest_discussions'))
        ->description(setting('name'))
        ->ttl(60)
        ->appendTo($feed);

        $discussions = \App\Models\Discussion::with('group')
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->whereIn('group_id', \App\Models\Group::public()->get()->pluck('id'))
        ->take(50)->get();

        foreach ($discussions as $discussion) {
            $item = new \Suin\RSSWriter\Item();
            $item
            ->title($discussion->name)
            ->description($discussion->body)
            ->contentEncoded($discussion->body)
            ->url(route('groups.discussions.show', [$discussion->group, $discussion]))
            ->author($discussion->user->name)
            ->pubDate($discussion->created_at->timestamp)
            ->guid(route('groups.discussions.show', [$discussion->group, $discussion]), true)
            ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
            ->appendTo($channel);
        }

        return response($feed, 200, ['Content-Type' => 'application/xml']);
    }

    public function actions()
    {
        $feed = new Feed();

        $channel = new Channel();
        $channel
        ->title(setting('name').' : '.trans('messages.agenda'))
        ->description(setting('name'))
        ->ttl(60)
        ->appendTo($feed);

        $actions = \App\Models\Action::with('group')
        ->with('user')
        ->whereIn('group_id', \App\Models\Group::public()->get()->pluck('id'))
        ->orderBy('start', 'desc')->take(50)->get();

        foreach ($actions as $action) {
            $item = new \Suin\RSSWriter\Item();
            $item
            ->title($action->name)
            ->description($action->body)
            ->contentEncoded($action->body)
            ->url(route('groups.actions.show', [$action->group, $action]))
            ->author($action->user->name)
            ->pubDate($action->start->timestamp)
            ->guid(route('groups.actions.show', [$action->group, $action]), true)
            ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
            ->appendTo($channel);
        }

        return response($feed, 200, ['Content-Type' => 'application/xml']);
    }
}
