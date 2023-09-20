<a name="comment_{{ $comment->id }}"></a>

<div class="mb-md-4 pb-md-4 mb-3 pb-3 comment border-bottom">

    <div class="d-flex">

        <div class="flex-grow-1 @if ($comment->isRead) read @endif">

            <div class="d-flex align-items-center mb-2">
                <div class="me-md-4 me-2">
                    @include('users.avatar', ['user' => $comment->user])
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold">
                        @if (isset($comment->user))
                            <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
                        @else
                            Unknown user
                        @endif
                    </div>
                    <div class=text-meta>
                        {{ dateForHumans($comment->created_at) }}
                    </div>
                </div>

                @can('update', $comment)
                    <div class="dropdown">
                        <a class="btn btn-pills" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" button">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">

                            @can('update', $comment)
                                <a class="dropdown-item"
                                    href="{{ action('CommentController@edit', [$comment->discussion->group, $comment->discussion, $comment]) }}">
                                    <i class="fa fa-pencil me-2"></i>
                                    {{ trans('messages.edit') }}
                                </a>
                            @endcan

                            @can('delete', $comment)
                                <a class="dropdown-item"
                                    href="{{ action('CommentController@destroyConfirm', [$comment->discussion->group, $comment->discussion, $comment]) }}"
                                    up-layer="new">
                                    <i class="fa fa-trash me-2"></i>
                                    {{ trans('messages.delete') }}
                                </a>
                            @endcan
                            <a class="dropdown-item"
                                href="{{ action('CommentController@history', [$comment->discussion->group, $comment->discussion, $comment]) }}">
                                <i class="fa fa-history me-2"></i> {{ trans('messages.show_history') }}</a>
                        </div>
                    </div>
                @endcan
            </div>

            <div>
                {!! filter($comment->body) !!}
            </div>

            <div>
                @include ('reactions.reactions', ['model' => $comment])
            </div>
        </div>

    </div>

</div>
