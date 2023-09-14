<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">

                @can('create-action', $group)
                    <a class="btn btn-primary" href="{{ route('groups.actions.create', $group) }}" up-follow>
                        {{ trans('action.create_one_button') }}
                    </a>
                @endcan

                <form role="search" method="GET" action="{{ route('groups.actions.index', $group) }}" up-autosubmit up-delay="500" up-target=".actions">
                    <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}" aria-label="Search" placeholder="{{ __('messages.search') }}...">
                </form>

            </div>
        @endif
    @endauth

    <div class="mt-4">
        @forelse($actions as $action)
            @include('actions.action')
        @empty
            @include('partials.empty')
        @endforelse
    </div>
</div>
