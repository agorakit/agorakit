<div class="d-flex justify-content-between align-items-start mb-md-4 pb-md-4 mb-3 pb-3 border-bottom" up-expand>

    @if ($discussion->user)
        <div class="me-md-3 me-2">
            @include('users.avatar', ['user' => $discussion->user])
        </div>
    @endif

    <div class="flex-grow-1">

        <div class="d-flex">
            <div class="flex-grow-1">

                <div>
                    <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                        @if ($discussion->isArchived())
                            [{{ __('Archived') }}]
                        @endif
                        {{ $discussion->name }}
                    </a>
                </div>

                <div class="text-meta">
                    @if ($discussion->user)
                        {{ trans('messages.started_by') }}
                        {{ $discussion->user->name }}
                    @endif
                    {{ trans('messages.in') }}
                    {{ $discussion->group->name }},
                    {{ dateForHumans($discussion->updated_at) }}
                </div>
            </div>
            @if ($discussion->isPinned())
                <div class="me-2">
                    <i class="fas fa-thumbtack" title="{{ __('Pinned') }}"></i>
                </div>
            @endif
            <div>
                @if ($discussion->unReadCount() > 0)
                    <span class="badge bg-primary rounded-pill">
                        {{ $discussion->unReadCount() }}
                    </span>
                @else
                    @if ($discussion->comments_count > 0)
                        <span class="badge bg-primary rounded-pill">
                            {{ $discussion->comments_count }}
                        </span>
                    @endif
                @endif
            </div>
            @include('discussions.dropdown')
        </div>

        <div class="">
            {{ summary($discussion->body) }}
        </div>

        @if ($discussion->getSelectedTags()->count() > 0)
            <div class="d-flex gap-1 flex-wrap">
                @foreach ($discussion->getSelectedTags() as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

    </div>

</div>
