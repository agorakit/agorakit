<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">
                @can('create-discussion', $group)
                    <a class="btn btn-primary me-2" href="{{ route('groups.discussions.create', $group) }}">
                        {{ trans('messages.create_discussion') }}
                    </a>
                @endcan

                <form action="{{ route('groups.discussions.index', $group) }}" method="GET" role="search" up-autosubmit
                    up-target=".discussions" up-watch-delay="500">
                    <input aria-label="Search" class="form-control" name="search"
                        placeholder="{{ __('messages.search') }}..." type="text" value="{{ Request::get('search') }}">
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
