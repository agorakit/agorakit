@extends('app')

@section('content')

  @include('groups.tabs')
  <div class="tab_content">




    <div class="discussion mb-5">

      <div class="d-flex justify-content-between">
        <h2 class="name">
          {{ $discussion->name }}
        </h2>

        <div class="ml-4 dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-wrench" aria-hidden="true"></i>

          </button>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

            @can('update', $discussion)
              <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$group->id, $discussion->id]) }}">
                <i class="fa fa-pencil"></i>
                {{trans('messages.edit')}}
              </a>
            @endcan

            @can('delete', $discussion)
              <a class="dropdown-item" href="{{ route('groups.discussions.deleteconfirm', [$group->id, $discussion->id]) }}">
                <i class="fa fa-trash"></i>
                {{trans('messages.delete')}}
              </a>
            @endcan

            @if ($discussion->revisionHistory->count() > 0)
              <a class="dropdown-item" href="{{route('groups.discussions.history', [$group->id, $discussion->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
            @endif
          </div>
        </div>

      </div>




      <div class="meta mb-3">
        {{trans('messages.started_by')}}
        <span class="user">
          <a href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name}}</a>
        </span>,
        {{trans('messages.in')}} {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}}
        @if ($discussion->tags->count() > 0)
          @foreach ($discussion->tags as $tag)
            <span class="badge tag">{{$tag->name}}</span>
          @endforeach
        @endif
      </div>

      <div class="body">
        {!! filter($discussion->body) !!}
      </div>

    </div>




    <div class="comments">
      @foreach ($discussion->comments as $comment_key => $comment)
        @include('comments._show')
      @endforeach

      @can('create-comment', $group)
        @include ('comments.create')
      @endcan
    </div>




  </div>
@endsection

@section('footer')
  <script>
  $(document).ready(function() {
    if ($("#unread").length)
    {
      $(document).scrollTop( $("#unread").offset().top-60 );
    }
  });
  </script>
@append
