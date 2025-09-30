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

    <div class="mt-5 js-calendar" data-create-url="/calendarevents/create" data-json="{{ action('CalendarEventController@indexJson') }}"
        data-locale="{{ App::getLocale() }}" id="calendar"></div>

    @include('dashboard.ical')
@endsection
