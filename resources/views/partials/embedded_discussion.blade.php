    <div style="max-width: 70em">
        <div class="discussion mb-4">

            @if ($total_count == 0)
                {{-- no comments yet, we scroll right here --}}
                <div id="unread"></div>
            @endif

            <div class="d-flex">

                <div class="flex-grow-1 ml-4">

                    <div class="d-flex align-items-center mb-1">

                        <div class="me-md-4 me-2">
                            @include('users.avatar', ['user' => $discussion->user])
                        </div>
                        <h2 class="mb-0 flex-grow-1">
                            {{ $discussion->name }}
                        </h2>
                        <div>
                            @include('discussions.dropdown')
                        </div>
                    </div>

                    <div class="text-meta mb-1">
                        @if (isset($discussion->user))
                            {{ trans('messages.started_by') }}
                            <span class="user">
                                <a href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name }}</a>
                            </span>
                        @endif
                        {{ trans('messages.in') }} {{ $discussion->group->name }}
                        {{ dateForHumans($discussion->created_at) }}
                    </div>

                    <div>
                        {!! filter($discussion->body) !!}
                    </div>

                    @if ($discussion->getSelectedTags()->count() > 0)
                        <div class="d-flex gap-1 flex-wrap mb-2">
                            @foreach ($discussion->getSelectedTags() as $tag)
                                @include('tags.tag')
                            @endforeach
                        </div>
                    @endif

                    <div>
                        @include ('reactions.reactions', ['model' => $discussion])
                    </div>
                </div>
            </div>
        </div>

        <div class="comments">
            @foreach ($discussion->comments as $comment_key => $comment)
                {{-- {{$comment_key}} / {{$read_count}} / {{$total_count}} --}}

                {{-- this is the first new unread comment --}}
                @if ($comment_key == $read_count)
                    <div class="d-flex justify-content-center pb-2" id="unread">
                        <div class="badge badge-pill bg-blue p-2">
                            <i class="far fa-arrow-alt-circle-down me-2"></i> {{ trans('messages.new') }}
                        </div>
                    </div>
                @endif

                @include('comments.comment')

                {{-- this is the latest comment, it is read, we scroll below after the comment post box --}}
                @if ($comment_key + 1 == $read_count)
                    <div class="d-flex justify-content-center pb-2" id="unread">
                        <div class="badge badge-pill bg-gray p-2">
                            <i class="far fa-arrow-alt-circle-up me-2"></i> {{ trans('messages.all_is_read') }}
                        </div>
                    </div>
                @endif
            @endforeach

            @auth
                @if (isset($comment))
                    <div id="live" up-poll
                        up-data='{"url": "{{ route('groups.discussions.live', [$group, $discussion, $comment]) }}"}'>
                        <div id="live-content"></div>
                    </div>
                @endif
            @endauth

            @can('create-comment', $group)
                @if ($discussion->isArchived())
                    <div class="alert alert-info">
                        @lang('This discussion is archived, you cannot comment anymore')
                    </div>
                @else
                    <div class="mt-2">
                        @include('comments.create')
                    </div>
                @endif
            @endcan

        </div>

    </div>
