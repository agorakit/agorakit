<a name="comment_{{ $comment->id }}"></a>

<div class="mb-3 pb-3 comment">

    <div class="d-flex">

        <div class="me-4">
            @include('users.avatar', ['user' => $comment->user])
        </div>

        <div class="w-100 flex-grow @if ($comment->isRead) read @endif">

            <div>
                {!! filter($comment->body) !!}
            </div>

            <div class="text-meta">
                @if (isset($comment->user))
                    <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
                @else
                    Unknown user
                @endif

                {{ dateForHumans($comment->created_at) }}
            </div>

            <div class="mb-2">
                @include ('reactions.reactions', ['model' => $comment])
            </div>
        </div>

        @can('update', $comment)
            <div class="dropdown">
                <a class="btn btn-pills" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" button">
                    <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                    @can('update', $comment)
                        <a class="dropdown-item" href="{{ action('CommentController@edit', [$comment->discussion->group, $comment->discussion, $comment]) }}">
                            <i class="fa fa-pencil me-2"></i>
                            {{ trans('messages.edit') }}
                        </a>
                    @endcan

                    @can('delete', $comment)
                        <a class="dropdown-item" href="{{ action('CommentController@destroyConfirm', [$comment->discussion->group, $comment->discussion, $comment]) }}" up-layer="new">
                            <i class="fa fa-trash me-2"></i>
                            {{ trans('messages.delete') }}
                        </a>
                    @endcan
                    <a class="dropdown-item" href="{{ action('CommentController@history', [$comment->discussion->group, $comment->discussion, $comment]) }}">
                        <i class="fa fa-history me-2"></i> {{ trans('messages.show_history') }}</a>
                </div>
            </div>
        @endcan

    </div>

</div>
