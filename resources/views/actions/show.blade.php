@extends('group')

@section('content')

    <div class="d-flex justify-content-between">
        <h1>
            <span class="me-2">{{ $action->name }}</span>
            @if ($action->isPublic())
                <span class="tag d-inline-block">{{ trans('messages.public') }}</span>
            @elseif ($action->isPrivate())
                <span class="tag d-inline-block">{{ trans('messages.private') }}</span>
            @endif
        </h1>
        @include('actions.dropdown')
    </div>

    <div class="row mb-4">

        <div class="col-12 col-md-6 mb-2 order-md-2">
            @if ($action->hasCover())
                <img class="rounded" src="{{ route('actions.cover.large', $action) }}" />
            @else
                <img class="rounded" src="/images/group.svg" />
            @endif
        </div>

        <div class="col-12 col-md-6">
            <div class="meta mb-3">
                {{ trans('messages.started_by') }}
                <span class="user">
                    @if ($action->user)
                        <a href="{{ route('users.show', [$action->user]) }}">{{ $action->user->name }}</a>
                    @endif
                </span>
                {{ trans('messages.in') }}
                <strong>
                    <a href="{{ route('groups.show', [$action->group]) }}">{{ $action->group->name }}</a>
                </strong>
                {{ dateForHumans($action->created_at) }}
            </div>

            <div class="tags mb-3">

            </div>
            <div class="tags mb-3">
                @if ($action->getSelectedTags()->count() > 0)
                    @foreach ($action->getSelectedTags() as $tag)
                        @include('tags.tag')
                    @endforeach
                @endif
            </div>

            <h3>{{ trans('messages.begins') }} : {{ $action->start->isoFormat('LLLL') }}</h3>

            <h3>{{ trans('messages.ends') }} : {{ $action->stop->isoFormat('LLLL') }}</h3>

            @if (!empty($action->location))
                <h3>{{ trans('messages.location') }} : {{ $action->location }}</h3>
            @endif

            <div>
                {!! filter($action->body) !!}
            </div>
            <div id="participate-{{ $action->id }}">
                <div class="mt-3">
                    @include('participation.dropdown')
                </div>

                @if ($action->attending->count() > 0)
                    <h3 class="mt-3">{{ trans('messages.user_attending') }} ({{ $action->attending->count() }})</h3>

                    <div class="btn-list">
                        @foreach ($action->attending as $user)
                            @include('users.user-card')
                        @endforeach
                    </div>
                @endif

                @if ($action->notAttending->count() > 0)
                    <h3 class="mt-3">{{ trans('messages.user_not_attending') }} ({{ $action->notAttending->count() }})
                    </h3>

                    <div class="btn-list">
                        @foreach ($action->notAttending as $user)
                            @include('users.user-card')
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
