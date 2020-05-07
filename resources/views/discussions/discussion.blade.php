<div up-expand class="discussion @if ($discussion->unReadCount() > 0) unread @endif">

    <div class="d-flex">
        <div class="avatar">
            <img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/>
        </div>


        <div class="w-100">
            <div>
                <div class="d-flex">
                    <div class="name">
                        <a up-follow up-reveal="false" href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                            {{ $discussion->name }}
                        </a>
                    </div>

                    <div class="ml-auto">
                        @if ($discussion->unReadCount() > 0)
                            <div class="d-flex align-items-start">
                                <div class="badge badge-danger" style="min-width: 2em">{{ $discussion->unReadCount() }} {{__('New')}}</div>
                            </div>
                        @else
                            @if ($discussion->comments_count > 0)
                                <div class="d-flex align-items-start">
                                    <div class="badge badge-secondary" style="min-width: 2em">{{ $discussion->comments_count }}</div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>




            </div>

            <div class="summary">
                {{summary($discussion->body) }}
            </div>

            <div class="meta">
                {{trans('messages.started_by')}}
                <strong>
                    <a up-follow href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name}}</a>
                </strong>
                {{trans('messages.in')}}
                <strong>
                    <a up-follow href="{{ route('groups.show', [$discussion->group]) }}">{{ $discussion->group->name}}</a>
                </strong>
                {{ $discussion->updated_at->diffForHumans()}}
            </div>

            <div class="tags">
                @if ($discussion->tags->count() > 0)
                    @foreach ($discussion->tags as $tag)
                        @include('tags.tag')
                    @endforeach
                @endif
            </div>



        </div>


    </div>


</div>
