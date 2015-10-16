
<div class="comment">

<div class="avatar"><img src="{{$comment->user->avatar()}}"/></div>

<div class="user">{{$comment->user->name}}</div>

<div class="body">{{$comment->body}}</div>

<div class="created">{{$comment->created_at->diffForHumans()}}</div>

<div class="vote"><a href="{{ action('VoteController@up', [$group->id, $discussion->id, $comment->id]) }}">Vote up</a></div>
<div class="vote"><a href="{{ action('VoteController@down', [$group->id, $discussion->id, $comment->id]) }}">Vote down</a></div>
<div class="vote"><a href="{{ action('VoteController@cancel', [$group->id, $discussion->id, $comment->id]) }}">Cancel vote</a></div>
<div class="vote">Current votes : {{ $comment->vote }}</div>


</div>
