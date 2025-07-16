@extends('app')

@section('content')
    <div class="d-md-flex gap-2 align-items-center justify-content-between mb-2">
        <div class="mb-2">
            @include('partials.preferences-calendar')
        </div>
        <a class="btn btn-primary" href="{{ route('calendarevents.create') }}">
            {{ trans('messages.create_event') }}
        </a>
    </div>



    @if ($events->count() > 0)
        <div class="actions mb-4">
            @include('calendarevents.list', ['calendarevents' => $events])
        </div>
        {{ $events->render() }}
    @else
        <div class="alert mt-4">
            {{ trans('messages.nothing_yet') }}
        </div>
    @endif

    @include('dashboard.ical')
@endsection
