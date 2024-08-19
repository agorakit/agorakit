<div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom" up-expand>

    <div class="flex-grow-1">
        <div class="d-flex align-items-center">
            @if ($discussion->user)
                <div class="me-md-3 me-2">
                    @include('users.avatar', ['user' => $discussion->user])
                </div>
            @endif

            <div class="flex-grow-1">
                <div class="summary">
                    <a class="fw-bold"
                        href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}#unread">
                        {{ $discussion->name }}
                        @if ($discussion->isArchived())
                            [{{ __('Archived') }}]
                        @endif
                    </a>
                </div>

                <div class="text-meta mb-1 summary">
                    @if ($discussion->user)
                        <span class="d-none d-md-inline">
                            {{ $discussion->user->name }}
                        </span>
                    @endif
                    <strong>
                        {{ $discussion->group->name }}
                    </strong>
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
                @endif
            </div>
            @include('discussions.dropdown')
        </div>

        <div class="summary">
            {{ summary($discussion->body, 150) }}
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
