<div class="col">
    <div class="card h-100 @if ($group->isArchived()) status-archived @endif" up-expand>

        @auth
            @if (Auth::user()->isAdminOf($group))
                <div class="ribbon bg-red">{{ __('membership.admin') }}</div>
            @elseif(Auth::user()->isMemberOf($group))
                <div class="ribbon bg-green">{{ __('membership.member') }}</div>
            @endif

            @if (Auth::user()->isCandidateOf($group))
                <div class="ribbon bg-gray">{{ __('membership.candidate') }}</div>
            @endif
        @endauth

        @if ($group->isPinned())
            <div class="ribbon ribbon-top bg-yellow" title="{{ trans('group.pinned') }}">
                <i class="far fa-star"></i>
            </div>
        @endif

        @if ($group->isArchived())
            <div class="ribbon ribbon-top bg-yellow" title="{{ trans('group.archived') }}">
                <i class="fas fa-archive"></i>
            </div>
        @endif

        <a href="{{ action('GroupController@show', $group) }}">
            @if ($group->hasCover())
                <img class="card-img-top" src="{{ route('groups.cover.medium', $group) }}" />
            @else
                <img class="card-img-top" src="/images/group.svg" />
            @endif
        </a>

        <div class="card-body">
            <h2 class="text-xl text-gray-800">
                {{ $group->name }}
                @if ($group->isOpen())
                    <i class="text-xs text-gray-500 fa fa-globe" title="{{ trans('group.open') }}"></i>
                @elseif($group->isClosed())
                    <i class="text-xs text-gray-500 fa fa-lock" title="{{ trans('group.closed') }}"></i>
                @else
                    <i class="text-xs text-gray-500 fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                @endif
            </h2>
            <div class="text-gray-700 mt-1 text-sm sm:text-xs flex-grow">
                {{ summary($group->body) }}

                @if ($group->tags->count() > 0)
                    <div class="text-gray-600 text-xs my-2">
                        @foreach ($group->getSelectedTags() as $tag)
                            @include('tags.tag')
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        <div class="card-footer text-body-secondary small">

            <div class="d-flex justify-content-between">
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
    </div>
</div>
