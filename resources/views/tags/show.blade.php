@extends('app')

@section('content')

  <div class="d-flex justify-content-between">
    <h1 class="name mb-4">
      <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
      <a href="{{ route('tags.index') }}">@lang('Tags')</a> <i class="fa fa-angle-right"></i>
      @lang('Items tagged with') <span class="badge badge-primary">{{ $tag }}</span>
    </h1>

    @auth
      <div class="d-flex mb-2">
        @include('partials.preferences-show')
      </div>
    @endauth

  </div>

  @if ($discussions->count() > 0)
    <div class="mb-5">
      <h2>@lang('Discussions')</h2>
      <div class="discussions items">
        @foreach( $discussions as $discussion )
          @include('discussions.discussion')
        @endforeach
      </div>
    </div>
  @endif

  @if ($actions->count() > 0)
    <div class="mb-5">
      <h2>@lang('Actions')</h2>
      <div class="actions items">
        @foreach( $actions as $action )
          @include('actions.action')
        @endforeach
      </div>
    </div>
  @endif

  @if ($files->count() > 0)
    <div class="mb-5">
      <h2>@lang('Files')</h2>
      <div class="files items">
        @foreach( $files as $file )
          @include('files.file')
        @endforeach
      </div>
    </div>
  @endif


  @if ($users->count() > 0)
    <div class="mb-5">
      <h2>@lang('Users')</h2>
      <div class="users items">
        @foreach( $users as $user )
          @include('users.user')
        @endforeach
      </div>
    </div>
  @endif


@endsection
