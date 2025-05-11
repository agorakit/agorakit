@extends('app')

@section('content')
    <div class="d-md-flex gap-2 align-items-center justify-content-between mb-2">
        <div class="mb-2">
            @include('partials.preferences-calendar')
        </div>
        <a class="btn btn-primary" href="{{ route('actions.create') }}">
            {{ trans('messages.create_action') }}
        </a>
    </div>

    <div class="mt-5 js-calendar" data-create-url="/actions/create" data-json="{{ action('ActionController@indexJson') }}"
        data-locale="{{ App::getLocale() }}" id="calendar"></div>

    @include('dashboard.ical')
@endsection
