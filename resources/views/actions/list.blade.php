<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">
                @can('create-action', $group)
                    <a class="btn btn-primary" href="{{ route('groups.actions.create', $group) }}">
                        {{ trans('messages.create_action') }}
                    </a>
                @endcan

                <form action="{{ route('groups.actions.index', $group) }}" method="GET" role="search" up-autosubmit up-target=".actions"
                    up-watch-delay="500">
                    <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('messages.search') }}..." type="text"
                        value="{{ Request::get('search') }}">
                </form>

            </div>
        @endif
    @endauth

    <div class="mt-4 row row-cards">

        @forelse($actions as $action)
            <div class="col-sm-6 col-lg-4">
                @include('actions.action')
            </div>
        @empty
            @include('partials.empty')
        @endforelse
    </div>

</div>
