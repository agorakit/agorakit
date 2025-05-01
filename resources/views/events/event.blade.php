<div class="card card-sm h-100" id="event-{{ $event->id }}" up-expand>

    @if ($event->hasCover())
        <img alt="event cover" class="card-img-top" src="{{ route('events.cover', [$event, 'medium']) }}" />
    @else
        <img alt="" class="card-img-top" src="/images/group.svg" />
    @endif


    <div class="card-body d-flex flex-column">
        <h5 class="card-title d-flex justify-content-between">
            <a href="{{ route('groups.events.show', [$event->group, $event]) }}">
                {{ $event->name }}
            </a>
            @include('events.dropdown')

        </h5>
        <h6 class="card-subtitle mb-2 text-body-secondary">
            {{ $event->start->isoFormat('ll') }}
            @if ($event->start->format('d') != $event->stop->format('d'))
                -
                {{ $event->stop->isoFormat('ll') }}
            @endif
        </h6>



        <div class="mb-2">
            {{ summary($event->body) }}
        </div>

        <div class="text-meta text-truncate mb-2">
            <div>
                @if ($event->isPublic())
                    <i class="fa fa-eye me-1"></i>{{ trans('messages.public') }}
                @elseif ($event->isPrivate())
                    <i class="fa fa-eye me-1"></i>{{ trans('messages.private') }}
                @endif
            </div>
            <div>
                <i class="fa fa-clock-o me-1"></i>
                {{ $event->start->isoFormat('LT') }}
                @if ($event->stop > $event->start)
                    - {{ $event->stop->isoFormat('LT') }}
                @endif
            </div>

            @if ($event->hasLocation())
                <div>
                    <i class="fa fa-map-marker me-1"></i> {{ $event->location_display("long") }}
                </div>
            @endif

            @if ($event->attending->count() > 0)
                <div>
                    <i class="fa fa-users me-1"></i> {{ $event->attending->count() }}
                    {{ trans('participants') }}
                </div>
            @endif
        </div>


        <div class="mt-auto" id="participate-{{ $event->id }}">
            @if ($event->attending->count() > 0)
                <div class="avatar-list avatar-list-stacked">
                    @foreach ($event->attending as $user)
                        @include('users.avatar')
                    @endforeach
                </div>
            @endif
            @can('participate', $event)
                <div class="mt-3">
                    @include('participation.dropdown')
                </div>
            @endcan
        </div>
    </div>

</div>
