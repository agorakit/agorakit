<div class="d-flex align-items-start mb-md-4 pb-md-4 mb-2 pb-2 border-bottom" id="action-{{ $action->id }}" up-expand>

    <div class="me-md-3 me-1 action-date">
        <div>
            <div class="fw-bold">{{ $action->start->format('d') }}</div>
            <div class="">{{ $action->start->isoFormat('MMM') }}</div>
        </div>
    </div>

    <div class="flex-grow text-truncate">

        <div class="mx-2">

            <div class="text-truncate">
                <a class="text-truncate d-block" href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                    {{ $action->name }}
                </a>
            </div>

            <div class="text-meta text-truncate">
                @if ($action->attending->count() > 0)
                    <div>
                        <i class="fa fa-users me-1"></i> {{ $action->attending->count() }}
                        {{ trans('participants') }}
                    </div>
                @endif

                <div>
                    <i class="fa fa-clock-o me-1"></i> {{ $action->start->format('H:i') }} -
                    {{ $action->stop->format('H:i') }}
                </div>

                @if ($action->location)
                    <div>
                        <i class="fa fa-map-marker me-1"></i> {{ $action->location }}
                    </div>
                @endif
            </div>

        </div>

        <div id="participate-{{ $action->id }}">
            @if ($action->attending->count() > 0)
                <div class="avatar-list avatar-list-stacked">
                    @foreach ($action->attending as $user)
                        @include('users.avatar')
                    @endforeach
                </div>
            @endif

            @can('participate', $action)
                <div class="mt-2">
                    @include('participation.dropdown')
                </div>
            @endcan

        </div>

    </div>

</div>
