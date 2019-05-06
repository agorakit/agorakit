@extends('app')

@section('content')

  

  <h1>
    <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
  </h1>

  <div class="d-md-flex justify-content-between">

    <div class="my-4">
      @include ('partials.preferences-show')
    </div>

    <div class="my-4">
      @include ('partials.preferences-calendar')
    </div>


    <div class="my-4">
      <a class="btn btn-primary" href="{{ route('actions.create') }}">
        <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
      </a>
    </div>
  </div>


  @if ($actions->count() > 0)
    <div class="actions">
      @foreach( $actions as $action)
        @include('actions.action')
      @endforeach
    </div>
    {{$actions->render()}}
  @else
    <div class="alert">
      {{trans('messages.nothing_yet')}}
    </div>
  @endif


  <p><a href="{{action('IcalController@index')}}">{{trans('messages.download_ical')}}</a></p>


@endsection
