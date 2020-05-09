@extends('app')

@section('content')

  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
      </h1>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <div class="d-md-flex justify-content-between">


    <div class="my-2">
      <a class="btn btn-primary" href="{{ route('actions.create') }}">
        <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
      </a>
    </div>


    <div class="my-2">
      @include ('partials.preferences-calendar')
    </div>

  </div>



  <div class="mt-5 calendar" id="calendar" data-json="{{action('ActionController@indexJson')}}" data-locale="{{App::getLocale()}}"></div>

  @include('dashboard.ical')


@endsection
