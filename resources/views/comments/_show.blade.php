
<div class="comment">

  <div class="avatar"><img src="{{$comment->user->avatar()}}"/></div>

  <div class="vote">
    <div class="up"><a href="{{ action('VoteController@up', [$group->id, $discussion->id, $comment->id]) }}"><span class="glyphicon glyphicon-arrow-up"</span></a></div>
    <div class="score"><a href="{{ action('VoteController@cancel', [$group->id, $discussion->id, $comment->id]) }}">{{ $comment->vote }}</a></div>
    <div class="down"><a href="{{ action('VoteController@down', [$group->id, $discussion->id, $comment->id]) }}"><span class="glyphicon glyphicon-arrow-down"</span></a></div>
  </div>

  <div class="user"><a href="{{ action('UserController@show', [$comment->user->id]) }}">{{$comment->user->name}}</a></div>

  <div class="body">{!! filter($comment->body) !!}</div>

  <div class="created">{{$comment->created_at->diffForHumans()}}</div>

  <div class="actions">
  @can('update', $comment)
    <a class="btn btn-default btn-xs" href="{{ action('CommentController@edit', [$group->id, $discussion->id, $comment->id]) }}"><i class="fa fa-pencil"></i>
    {{trans('messages.edit')}}</a>
  @endcan

  @can('delete', $comment)
    <a class="btn btn-default btn-xs" href="{{ action('CommentController@destroyConfirm', [$group->id, $discussion->id, $comment->id]) }}"><i class="fa fa-trash"></i>
    {{trans('messages.delete')}}</a>
  @endcan


  @if ($comment->revisionHistory->count() > 0)
  <a class="btn btn-default btn-xs" href="{{action('CommentController@history', [$group->id, $discussion->id, $comment->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
  @endif
</div>

<div style="clear:both"></div>




</div>
