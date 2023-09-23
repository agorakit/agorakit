@extends('app')

@section('content')
    <div class="d-flex ">
        <h1>
            <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
            {{ trans('messages.agenda') }}
        </h1>
    </div>

    <div class="mb-2">
        @include('partials.preferences-show')
    </div>

    <div class="d-flex justify-content-between">

        <div>
            @include('partials.preferences-calendar')
        </div>

        <div class="">

            <a class="btn btn-primary" href="{{ route('actions.create') }}">
                <span class="hidden md:inline ml-2">
                    {{ trans('action.create_one_button') }}
                </span>
            </a>
        </div>
    </div>

    <div class="mt-5 js-calendar" id="calendar" data-json="{{ action('ActionController@indexJson') }}"
        data-locale="{{ App::getLocale() }}" data-create-url="/actions/create"></div>

    @include('dashboard.ical')
@endsection
