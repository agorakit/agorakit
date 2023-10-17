<div class="mb-md-4 pb-md-4 mb-3 pb-3 border-bottom flex-grow" id="action-{{ $action->id }}" up-expand>

    <div class="d-flex mb-2 align-items-center ">
        <div class="me-md-3 me-1">
            <div class="action-date">
                <div class="fw-bold -mb-2">{{ $action->start->format('d') }}</div>
                <div class="">{{ $action->start->isoFormat('MMM') }}</div>
            </div>
        </div>

        <div class="flex-fill text-truncate">
            <div class="mx-2">
                <div class="text-truncate d-flex flex-wrap gap-1">
                    <a class="text-truncate d-block fw-bold"
                        href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                        {{ $action->name }}
                    </a>
                </div>
            </div>
        </div>
        @include('actions.dropdown')
    </div>

    <div class="row">

        @if ($action->hasCover())
            <div class="col-12 col-sm-5 col-md-4 mb-2 order-sm-2">
                <img class="rounded" src="{{ route('actions.cover.large', $action) }}" />
            </div>
        @endif

        <div class="col-12 col-sm-7 col-md-8">
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
                    <i class="fa fa-clock-o me-1"></i> {{ $action->start->format('H:i') }} -
                    {{ $action->stop->format('H:i') }}
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

</div>
