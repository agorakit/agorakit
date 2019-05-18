@extends('app')

@section('content')


    <div class="tags items">
        @forelse( $tags as $tag )
          <a href="{{route('tags.show', $tag->name)}}" class="badge badge-primary">{{$tag->name}}</a>
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse

    </div>



@endsection
