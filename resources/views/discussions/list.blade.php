<div class="">
    @auth

        <div class="d-flex justify-content-between mb-2">
            
                <a class="btn btn-primary me-2" href="{{ route('discussions.create', $group) }}">
                    {{ trans('messages.create_discussion') }}
                </a>
            

            <form action="{{ route('discussions') }}" method="GET" role="search" up-autosubmit up-target=".discussions"
                up-watch-delay="500">
                <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('messages.search') }}..." type="text"
                    value="{{ Request::get('search') }}">
            </form>

        </div>

    @endauth

    <div class="mt-4">
        @forelse($discussions as $discussion)
            @include('discussions.discussion')
        @empty
            @include('partials.empty')
        @endforelse
    </div>

    <div class="mt-16 text-secondary">
        <a class="btn btn-secondary" href="{{ route('discussions.feed') }}"><i class="fas fa-rss"></i> RSS</a>
    </div>
</div>
