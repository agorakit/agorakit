@extends('app')

@section('content')


<div class="d-flex ">
    <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i
            class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
    </h1>
</div>



<div class="flex justify-between">

    <div class="flex ">
        <div class="mr-4">
            @include('partials.preferences-show')
        </div>
        <div>
            @include('partials.preferences-calendar')
        </div>
    </div>


    <div class="">
        <a up-follow class="btn btn-primary" href="{{ route('actions.create') }}">
            <i class="fas fa-pencil-alt"></i>
            <span class="hidden md:inline ml-2">
                {{ trans('action.create_one_button') }}
            </span>
        </a>
    </div>




</div>



<div class="mt-5 calendar" id="calendar" data-json="{{ action('ActionController@indexJson') }}"
    data-locale="{{ App::getLocale() }}" data-create-url="/actions/create"></div>

@include('dashboard.ical')


@endsection