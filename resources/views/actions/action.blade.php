<div class="card card-sm h-100" id="action-{{ $action->id }}" up-expand>

    @if ($action->hasCover())
        <img alt="action cover" class="card-img-top" src="{{ route('actions.cover', [$action, 'medium']) }}" />
    @else
        <img alt="" class="card-img-top" src="/images/group.svg" />
    @endif


    <div class="card-body">
        <h5 class="card-title d-flex justify-content-between align-items-center">
            <a href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                {{ $action->name }}
            </a>
            @include('actions.dropdown')

        </h5>
        <h6 class="card-subtitle mb-2 text-body-secondary">
            {{ $action->start->isoFormat('ll') }}
            @if ($action->start->format('d') != $action->stop->format('d'))
                -
                {{ $action->stop->isoFormat('ll') }}
            @endif
        </h6>



        <div class="mb-2">
            {{ summary($action->body) }}
        </div>

        <div class="text-meta text-truncate mb-2">
            <div>
                @if ($action->isPublic())
                    <i class="fa fa-eye me-1"></i>{{ trans('messages.public') }}
                @elseif ($action->isPrivate())
                    <i class="fa fa-eye me-1"></i>{{ trans('messages.private') }}
                @endif
            </div>
            <div>
                <i class="fa fa-clock-o me-1"></i> {{ $action->start->format('H:i') }}
                @if ($action->stop > $action->start)
                    - {{ $action->stop->format('H:i') }}
                @endif
            </div>

            @if ($action->location)
                <div>
                    <i class="fa fa-map-marker me-1"></i> {{ $action->location }}
                </div>
            @endif

            @if ($action->attending->count() > 0)
                <div>
                    <i class="fa fa-users me-1"></i> {{ $action->attending->count() }}
                    {{ trans('participants') }}
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
