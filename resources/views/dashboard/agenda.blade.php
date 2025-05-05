@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-md-flex gap-2 align-items-center justify-content-between mb-2">
        <div class="mb-2">
            @include('partials.preferences-calendar')
        </div>
        <a class="btn btn-primary" href="{{ route('events.create') }}">
            {{ trans('messages.create_event') }}
        </a>
    </div>

    <div class="mt-5 js-calendar" data-create-url="/events/create" data-json="{{ action('EventController@indexJson') }}"
        data-locale="{{ App::getLocale() }}" id="calendar"></div>

    @include('dashboard.ical')
@endsection
