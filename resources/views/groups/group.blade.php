<div class="card @if ($group->isArchived()) status-archived @endif" up-expand>

    <div class="header">
        @if ($group->isPinned())
            <div class="pinned" title="{{ trans('group.pinned') }}">
                <i class="far fa-star"></i>
            </div>
        @endif

        @if ($group->isArchived())
            <div class="archived" title="{{ trans('group.archived') }}">
                <i class="fas fa-archive"></i>
            </div>
        @endif

        <a class="cover" href="{{ action('GroupController@show', $group) }}">
            @if ($group->hasCover())
                <img src="{{ route('groups.cover', [$group, 'medium']) }}" />
            @else
                <img src="/images/group.svg" />
            @endif

            @auth
                @if (Auth::user()->isAdminOf($group))
                    <div class="admin">{{ __('membership.admin') }}
                    </div>
                @elseif(Auth::user()->isMemberOf($group))
                    <div class="member">{{ __('membership.member') }}
                    </div>
                @endif
                @if (Auth::user()->isCandidateOf($group))
                    <div class="candidate">{{ __('membership.candidate') }}
                    </div>
                @endif
            @endauth

        </a>
    </div>

    <div class="body">
        <h2>
            {{ $group->name }}
            @if ($group->isOpen())
                <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
            @elseif($group->isClosed())
                <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
            @else
                <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
            @endif
        </h2>

        <div>
            {{ summary($group->body) }}

            @if ($group->tags->count() > 0)
                <div class="tags">
                    @foreach ($group->getSelectedTags() as $tag)
                        @include('tags.tag')
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <div class="footer">
        <div>
            <i class="far fa-comments me-1"></i>
            <span> {{ $group->discussions->count() }}</span>
        </div>

        <div>
            <i class="far fa-calendar-alt me-1"></i>
            <span>{{ $group->actions->count() }}</span>
        </div>
        <div>
            <i class="fas fa-users me-1"></i>
            <span>{{ $group->users->count() }}</span>
        </div>

        <div>
            <i class="far fa-lightbulb me-1"></i>
            <span>{{ $group->updated_at->diffForHumans() }}</span>
        </div>

    </div>
</div>
