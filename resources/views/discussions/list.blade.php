<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">
                @can('create-discussion', $group)
                    <a class="btn btn-primary me-2" href="{{ route('groups.discussions.create', $group) }}" >
                        {{ trans('discussion.create_one_button') }}
                    </a>
                @endcan

                <form role="search" method="GET" action="{{ route('groups.discussions.index', $group) }}" up-autosubmit up-watch-delay="500" up-target=".discussions">
                    <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}" aria-label="Search" placeholder="{{ __('messages.search') }}...">
                </form>

            </div>
        @endif
    @endauth

    <div class="mt-4">
        @forelse($discussions as $discussion)
            @include('discussions.discussion')
        @empty
            @include('partials.empty')
        @endforelse
    </div>
</div>
