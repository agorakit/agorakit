<a name="comment_{{$comment->id}}"></a>

<div class="comment
@if ($comment_key + 2 > $read_comments) unread @else read @endif"
    @if ($comment_key + 2 == $read_comments) id="unread" @endif>

        <div class="d-flex">

            <div class="avatar mr-3"><img src="{{route('users.cover', [$comment->user, 'small'])}}" class="rounded-circle"/></div>

            <div class="w-100">
                <div class="user"><a href="{{ route('users.show', [$comment->user]) }}">{{$comment->user->name}}</a></div>

                <div class="body">{!! filter($comment->body) !!}</div>



                <div class="d-flex justify-content-between">
                    <div class="meta">{{$comment->created_at->diffForHumans()}}</div>


                    @can('update', $comment)
                    <div class="ml-4 dropdown">
                        <button class="btn btn-secondary-outline dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-wrench" aria-hidden="true"></i>

                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            @can('update', $comment)
                                <a class="dropdown-item" href="{{ action('CommentController@edit', [$group, $discussion, $comment]) }}"><i class="fa fa-pencil"></i>
                                    {{trans('messages.edit')}}
                                </a>
                            @endcan

                            @can('delete', $comment)
                                <a class="dropdown-item" up-modal=".dialog" href="{{ action('CommentController@destroyConfirm', [$group, $discussion, $comment]) }}"><i class="fa fa-trash"></i>
                                    {{trans('messages.delete')}}
                                </a>
                            @endcan

                            @if ($comment->revisionHistory->count() > 0)
                                <a class="dropdown-item" href="{{action('CommentController@history', [$group, $discussion, $comment])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                            @endif
                        </div>
                    </div>
                @endcan
                </div>
            </div>
        </div>
    </div>
