@extends('app')

@section('content')

  <ul class="list-group">
    @foreach ($all_tags as $tag)

      @if ($model_tags->contains($tag))
        <a href="remove " class="list-group-item list-group-item-action" style="background-color: {{$tag->color}}">
          {{$tag->name}} x
        </a>
      @else
      <a href="add" class="list-group-item list-group-item-action">
        {{$tag->name}}
      </a>
    @endif
    @endforeach
  </ul>

@endsection
