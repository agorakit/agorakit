@extends('app')

@section('content')

  <h2 class="name">
    Items tagged with <span class="badge badge-primary">{{ $tag }}</span>
  </h2>

  <h3>Discussions</h3>

  <div class="discussions items">
    @forelse( $discussions as $discussion )
      @include('discussions.discussion')
    @empty
      {{trans('messages.nothing_yet')}}
    @endforelse

  </div>


  <h3>Files</h3>

  <div class="files items">
    @forelse( $files as $file )
      @include('files.file')
    @empty
      {{trans('messages.nothing_yet')}}
    @endforelse

  </div>



@endsection
