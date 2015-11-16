
<div class="comment">

  <div class="avatar"><img src="{{$comment->user->avatar()}}"/></div>

  <div class="vote">
    <div class="up"><a href="{{ action('VoteController@up', [$group->id, $discussion->id, $comment->id]) }}"><span class="glyphicon glyphicon-arrow-up"</span></a></div>
    <div class="score"><a href="{{ action('VoteController@cancel', [$group->id, $discussion->id, $comment->id]) }}">{{ $comment->vote }}</a></div>
    <div class="down"><a href="{{ action('VoteController@down', [$group->id, $discussion->id, $comment->id]) }}"><span class="glyphicon glyphicon-arrow-down"</span></a></div>
  </div>

  <div class="user">{{$comment->user->name}}</div>

  <div class="body">{!!$comment->body!!}</div>

  <div class="created">{{$comment->created_at->diffForHumans()}}</div>







</div>
