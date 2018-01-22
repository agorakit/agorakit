<a name="comment_{{$comment->id}}"></a>

<div class="comment @if ($comment_key + 2 > $read_comments) unread @else read @endif"
    @if ($comment_key + 2 == $read_comments) id="unread" @endif>

        <div class="avatar"><img src="{{$comment->user->avatar()}}" class="rounded-circle"/></div>
        
        <div class="user"><a href="{{ route('users.show', [$comment->user->id]) }}">{{$comment->user->name}}</a></div>

        <div class="body">{!! filter($comment->body) !!}</div>

        <div class="created">{{$comment->created_at->diffForHumans()}}</div>

        <div class="actions">
            @can('update', $comment)
                <a class="btn btn-primary btn-xs" href="{{ action('CommentController@edit', [$group->id, $discussion->id, $comment->id]) }}"><i class="fa fa-pencil"></i>
                    {{trans('messages.edit')}}</a>
                @endcan

                @can('delete', $comment)
                    <a class="btn btn-primary btn-xs" href="{{ action('CommentController@destroyConfirm', [$group->id, $discussion->id, $comment->id]) }}"><i class="fa fa-trash"></i>
                        {{trans('messages.delete')}}</a>
                    @endcan


                    @if ($comment->revisionHistory->count() > 0)
                        <a class="btn btn-primary btn-xs" href="{{action('CommentController@history', [$group->id, $discussion->id, $comment->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                    @endif
                </div>

                <div style="clear:both"></div>




            </div>
