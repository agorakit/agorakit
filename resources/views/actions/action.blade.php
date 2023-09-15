<div class="d-flex align-items-start mb-4 pb-4 border-bottom" id="action-{{ $action->id }}" up-expand>

    <div class="btn btn-outline-secondary me-3">
        <div>
            <div class="display-6">{{ $action->start->format('d') }}</div>
            <div class="">{{ $action->start->isoFormat('MMM') }}</div>
        </div>
    </div>

    <div class="flex-grow">

        <div class="mx-2">

            <div class="text-gray-900 text-lg truncate">
                <a class="no-underline" href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                    {{ $action->name }}
                </a>
            </div>
            @if ($action->attending->count() > 0)
                <div class="text-secondary text-xs">
                    <i class="fa fa-users"></i> {{ $action->attending->count() }}
                    {{ trans('participants') }}
                </div>
            @endif

            <div class="text-secondary text-xs">
                <i class="fa fa-clock-o"></i> {{ $action->start->format('H:i') }} -
                {{ $action->stop->format('H:i') }}
            </div>

            @if ($action->location)
                <div class="text-secondary text-xs overflow-ellipsis overflow-hidden h-4">
                    <i class="fa fa-map-marker"></i> {{ $action->location }}
                </div>
            @endif

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
