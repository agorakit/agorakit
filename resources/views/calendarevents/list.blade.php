<div class="">
    @auth
        @if (isset($group))
            <div class="d-flex justify-content-between mb-2">
                @can('create-calendarevent', $group)
                    <a class="btn btn-primary" href="{{ route('groups.calendarevents.create', $group) }}">
                        {{ trans('messages.create_event') }}
                    </a>
                @endcan

                <form action="{{ route('groups.calendarevents.index', $group) }}" method="GET" role="search" up-autosubmit up-target=".events"
                    up-watch-delay="500">
                    <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('messages.search') }}..." type="text"
                        value="{{ Request::get('search') }}">
                </form>

            </div>
        @endif
    @endauth

    <div class="mt-4 row row-cards">

        @forelse($events as $event)
            <div class="col-sm-6 col-lg-4">
                @include('calendarevents.calendarevent')
            </div>
        @empty
            @include('partials.empty')
        @endforelse
    </div>

</div>
