@extends('app')

@section('content')






  <div class="d-flex justify-content-between">
    <h1 class="name mb-4">
      <a  href="{{ route('index') }}">
      <i class="fa fa-home"></i></a> 
      <i class="fa fa-angle-right"></i>
      @lang('Tags')
    </h1>

    @auth
      <div class="d-flex mb-2">
        @include('partials.preferences-show')
      </div>
    @endauth

  </div>



  <div class="d-flex gap-1 flex-wrap">
      @forelse( $tags as $tag )
        @include('tags.tag')
      @empty
        {{trans('messages.nothing_yet')}}
      @endforelse
  </div>



@endsection
