@extends('app')

@section('content')

    <div class="d-flex justify-content-between">
        <h1>
            <span class="me-2">{{ $event->name }}</span>
            @if ($event->isPublic())
                <span class="tag d-inline-block">{{ trans('messages.public') }}</span>
            @elseif ($event->isPrivate())
                <span class="tag d-inline-block">{{ trans('messages.private') }}</span>
            @endif
        </h1>
        @include('calendarevents.dropdown')
    </div>

    <div class="row mb-4">


        @if ($event->hasCover())
            <div class="col-12 col-sm-5 col-md-4 mb-2 order-sm-2">
                <img alt="event cover" class="rounded" src="{{ route('calendarevents.cover', [$event, 'large']) }}" />
            </div>
        @endif


        <div class="col-12 col-sm-7 col-md-8">
            <div class="meta mb-3">
                {{ trans('messages.started_by') }}
                <span class="user">
                    @if ($event->user)
                        <a href="{{ route('users.show', [$event->user]) }}">{{ $event->user->name }}</a>
                    @endif
                </span>
                {{ trans('messages.in') }}
                <strong>
                    <a href="{{ route('groups.show', [$event->group]) }}">{{ $event->group->name }}</a>
                </strong>
                {{ dateForHumans($event->created_at) }}
            </div>

            <div class="tags mb-3">

            </div>
            <div class="tags mb-3">
                @if ($event->getSelectedTags()->count() > 0)
                    @foreach ($event->getSelectedTags() as $tag)
                        @include('tags.tag')
                    @endforeach
                @endif
            </div>

            <h3>{{ trans('messages.begins') }} : {{ $event->start->isoFormat('LLLL') }}</h3>

            @if ($event->stop > $event->start) <h3>{{ trans('messages.ends') }} : {{ $event->stop->isoFormat('LLLL') }}</h3>
            @endif

            @if ($event->hasLocation())
                <div class="fw-bold">{{ trans('messages.location') }}</div>
                {{ $event->location_display("long") }}
            @endif

            <div>
                {!! filter($event->body) !!}
            </div>
            <div id="participate-{{ $event->id }}">
                <div class="mt-3">
                    @include('participation.dropdown')
                </div>

                @if ($event->attending->count() > 0)
                    <h3 class="mt-3 mb-1">{{ trans('messages.user_attending') }} ({{ $event->attending->count() }})</h3>

                    <div class="btn-list">
                        @foreach ($event->attending as $user)
                            @include('users.user-card')
                        @endforeach
                    </div>
                @endif

                @if ($event->notAttending->count() > 0)
                    <h3 class="mt-3 mb-1">{{ trans('messages.user_not_attending') }}
                        ({{ $event->notAttending->count() }})
                    </h3>

                    <div class="btn-list">
                        @foreach ($event->notAttending as $user)
                            @include('users.user-card')
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
