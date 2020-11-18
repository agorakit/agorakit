<a name="comment_{{$comment->id}}"></a>

<div class="mb-3 pb-3 comment">

    <div class="flex">

        <img src="{{route('users.cover', [$comment->user, 'small'])}}" class="rounded-full h-10 w-10 sm:h-12 sm:w-12 flex-shrink-0 mr-2 sm:mr-4"/>

        <div class="w-100 flex-grow mb-2">
            <div class="d-flex align-items-center">
                <div class="user">
                    <a up-follow href="{{ route('users.show', [$comment->user]) }}">{{$comment->user->name}}</a>
                </div>
                <div class="text-xs text-gray-600">
                    {{$comment->created_at->diffForHumans()}}
                </div>
            </div>
        </div>

        @can('update', $comment)
            <div class="dropdown">
                <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

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
                    <a class="dropdown-item" href="{{action('CommentController@history', [$group, $discussion, $comment])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                </div>
            </div>
        @endcan

    </div>

    <div class="body sm:ml-16 lg:mr-40">
    {!! filter($comment->body) !!}
    </div>


</div>
