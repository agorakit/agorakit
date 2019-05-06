@extends('app')

@section('content')


  <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.discussions') }}</h1>


  <div class="d-md-flex justify-content-between">
    <div class="my-4">
      @include ('partials.preferences-show')
    </div>

    <div class="my-4">
      <a class="btn btn-primary" href="{{ route('discussions.create') }}">
        <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
      </a>
    </div>
  </div>


  <div class="discussions">
    @forelse( $discussions as $discussion )
      @include('discussions.discussion')
    @empty
      {{trans('messages.nothing_yet')}}
    @endforelse
    {!! $discussions->render() !!}
  </div>



@endsection
