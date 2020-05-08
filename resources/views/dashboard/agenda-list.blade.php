@extends('app')

@section('content')


  <div class="toolbox d-md-flex">
    <div class="d-flex">
      <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
      </hi>
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

  @if ($actions->count() > 0)
    <div class="actions mt-5">
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


  @include('dashboard.ical')


@endsection
