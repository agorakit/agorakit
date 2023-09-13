<a name="comment_{{$comment->id}}"></a>

<div class="mb-6 pb-6 comment">

    <div class="flex">

        @include('users.avatar', ['user' => $comment->user])

        <div class="w-100 flex-grow ml-4 @if ($comment->isRead) overflow-hidden text-gray-700 h-12 read @endif">
            {{-- <--- TODO here me manage collapsed comments --}}
            <div>
                {!! filter($comment->body) !!}
            </div>



            <div class="text-xs text-secondary">

                <a up-follow href="{{ route('users.show', [$comment->user]) }}">{{$comment->user->name}}</a>

                {{ dateForHumans($comment->created_at) }}
            </div>

            <div>
                <x-reactions :model="$comment" />
            </div>
        </div>



        @can('update', $comment)
        <div class="dropdown">
            <a class="rounded-full hover:bg-gray-400 w-10 h-10 d-flex align-items-center justify-center type=" button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                @can('update', $comment)
                <a class="dropdown-item"
                    href="{{ action('CommentController@edit', [$comment->discussion->group, $comment->discussion, $comment]) }}"><i
                        class="fa fa-pencil"></i>
                    {{trans('messages.edit')}}
                </a>
                @endcan

                @can('delete', $comment)
                <a class="dropdown-item" up-layer="new"
                    href="{{ action('CommentController@destroyConfirm', [$comment->discussion->group, $comment->discussion, $comment]) }}"><i
                        class="fa fa-trash"></i>
                    {{trans('messages.delete')}}
                </a>
                @endcan
                <a class="dropdown-item"
                    href="{{action('CommentController@history', [$comment->discussion->group, $comment->discussion, $comment])}}"><i
                        class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
            </div>
        </div>
        @endcan

    </div>




</div>