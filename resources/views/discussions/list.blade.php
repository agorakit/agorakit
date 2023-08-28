<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">

                <form role="search" method="GET" action="{{ route('groups.discussions.index', $group) }}" up-autosubmit up-delay="500" up-target=".discussions">
                    <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}" aria-label="Search" placeholder="{{ __('messages.search') }}...">
                </form>

                @can('create-discussion', $group)
                    <a class="btn btn-primary" href="{{ route('groups.discussions.create', $group) }}" up-follow>
                        {{ trans('discussion.create_one_button') }}
                    </a>
                @endcan

            </div>
        @endif
    @endauth

    <div class="list-group discussions">
        @forelse($discussions as $discussion)
            @include('discussions.discussion')
        @empty
            @include('partials.empty')
        @endforelse
    </div>
</div>
