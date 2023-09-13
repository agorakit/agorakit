<div class="list-group-item d-flex justify-content-between align-items-start" up-expand>

    @if ($discussion->user)
        <div class="me-4">
            @include('users.avatar', ['user' => $discussion->user])
        </div>
    @endif

    <div class="flex-grow-1 text-truncate">

        <div class="text-truncate mt-n1">
            <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                @if ($discussion->isArchived())
                    [{{ __('Archived') }}]
                @endif
                {{ $discussion->name }}
            </a>
        </div>

        <div class="text-secondary text-xs">
            @if ($discussion->user)
                {{ trans('messages.started_by') }}
                {{ $discussion->user->name }}
            @endif
            {{ trans('messages.in') }}
            {{ $discussion->group->name }},
            {{ dateForHumans($discussion->updated_at) }}
        </div>

        <div class="text-secondary text-truncate d-block">
            {{ summary($discussion->body) }}
        </div>

        @if ($discussion->getSelectedTags()->count() > 0)
            <div class="text-secondary text-xs overflow-hidden">
                @foreach ($discussion->getSelectedTags() as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

    </div>

    <div class="d-flex ms-4">

        @if ($discussion->isPinned())
            <div class="">
                <i class="fas fa-thumbtack" title="{{ __('Pinned') }}"></i>
            </div>
        @endif

        <div class="me-2">
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
</div>
