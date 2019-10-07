@extends('app')

@section('content')

  <ul class="list-group" style="min-width: 20rem">
    @foreach ($all_tags as $tag)

      @if ($model_tags->contains($tag))
        <a up-target=".list-group" href="{{ route('groups.discussions.tags.delete', [$model->group, $model, $tag]) }}" class="list-group-item list-group-item-action">
          <div class="d-flex">
            <div style="width: 2rem"><i class="fas fa-check"></i></div>
            <div style="background-color: {{$tag->color}}; width: 1rem; height :1rem" class="m-1"></div>
            <div class="flex-grow-1">{{$tag->name}}</div>
            <div><i class="fas fa-times"></i></div>
          </div>
        </a>
      @else

        <a up-target=".list-group" href="{{ route('groups.discussions.tags.create', [$model->group, $model, $tag]) }}" class="list-group-item list-group-item-action">
          <div class="d-flex">
            <div style="width: 2rem"></div>
            <div style="background-color: {{$tag->color}}; width: 1rem; height :1rem" class="m-1"></div>
            <div style="color: #aaa">{{$tag->name}}</div>
          </div>
        </a>
      @endif
    @endforeach
  </ul>

@endsection
