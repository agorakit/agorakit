@extends('app')

@section('content')



  <h1 class="name mb-4">
    <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    <a href="{{ route('tags.index') }}">Tags</a> <i class="fa fa-angle-right"></i>
    Items tagged with <span class="badge badge-primary">{{ $tag }}</span>
  </h1>

  @if ($discussions->count() > 0)
    <div class="mb-5">
      <h2>Discussions</h2>
      <div class="discussions items">
        @foreach( $discussions as $discussion )
          @include('discussions.discussion')
        @endforeach
      </div>
    </div>
  @endif

  @if ($actions->count() > 0)
    <div class="mb-5">
      <h2>Actions</h2>
      <div class="actions items">
        @foreach( $actions as $action )
          @include('actions.action')
        @endforeach
      </div>
    </div>
  @endif

  @if ($files->count() > 0)
    <div class="mb-5">
      <h2>Files</h2>
      <div class="files items">
        @foreach( $files as $file )
          @include('files.file')
        @endforeach
      </div>
    </div>
  @endif


  @if ($users->count() > 0)
    <div class="mb-5">
      <h2>Users</h2>
      <div class="users items">
        @foreach( $users as $user )
          @include('users.user')
        @endforeach
      </div>
    </div>
  @endif


@endsection
