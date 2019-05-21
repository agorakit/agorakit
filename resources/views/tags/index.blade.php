@extends('app')

@section('content')


  <h1 class="name mb-4">
    <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    @lang('Tags')
  </h1>


    <div class="tags items">
        @forelse( $tags as $tag )
          <a href="{{route('tags.show', $tag->name)}}" class="badge badge-primary">{{$tag->name}}</a>
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse
    </div>



@endsection
