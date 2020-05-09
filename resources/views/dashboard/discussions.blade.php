@extends('app')

@section('content')




  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1><a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.discussions') }}</h1>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('discussions.create') }}">
      <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
    </a>
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
