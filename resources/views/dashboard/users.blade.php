@extends('app')

@section('content')

  
  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.users') }}
      </hi>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <div class="users">
    @if ($users)
      @foreach($users as $user)
        @include('users.user')
      @endforeach
      {!! $users->render() !!}
    @else
      {{trans('messages.nothing_yet')}}
    @endif
  </div>


@endsection
