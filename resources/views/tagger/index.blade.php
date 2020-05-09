@extends('dialog')

@section('content')

  <div class="my-3">

    <h2>Edit tags for <strong>"{{$model->name}}"</strong></h2>

    <div class="tagger">
      @foreach ($allowed_tags as $allowed_tag)
        @if ($used_tags->contains($allowed_tag))
          <a up-target=".tagger" up-cache="false" up-history="false" href="{{route('tagger.tag', [$type, $id, $allowed_tag->name])}}">
            <span class="badge badge-pill" style="color: white; background-color: {{$allowed_tag->color}}">

              <i class="fas fa-check-square mr-1"></i>
              {{$allowed_tag->name}}

            </span>
          </a>
        @else
          <a up-target=".tagger" up-cache="false" up-history="false" href="{{route('tagger.tag', [$type, $id, $allowed_tag->name])}}">
            <span class="badge badge-pill" style="color: white; background-color: {{$allowed_tag->color}}">
              <i class="fas fa-square mr-1"></i>
              {{$allowed_tag->name}}
            </span>
          </a>
        @endif
      @endforeach
    </div>

    <a href="{{$return_to}}" up-target=".tags" up-cache="false" class="btn btn-primary">Done</a>


  </div>

@endsection
