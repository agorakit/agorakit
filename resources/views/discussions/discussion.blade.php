<div class="discussion @if ($discussion->unReadCount() > 0) unread @endif">

  <div class="avatar"><img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/></div>
  <div class="content w-100">
    <div class="d-flex justify-content-between align-items-start">
      <span class="name">
        <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
          {{ $discussion->name }}
        </a>
        @if ($discussion->tags->count() > 0)
          @foreach ($discussion->tags as $tag)
            <span class="badge tag">{{$tag->name}}</span>
          @endforeach
        @endif
      </span>
      @if ($discussion->unReadCount() > 0)
        <div class="badge-unread">{{ $discussion->unReadCount() }}</div>
      @endif
    </div>

    <span class="summary">
      <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
        {{summary($discussion->body) }}
      </a>
    </span>

    <br/>

    <div class="d-flex justify-content-between">
      <a href="{{ route('groups.show', [$discussion->group_id]) }}">
        <span class="badge badge-secondary badge-group">
          @if ($discussion->group->isOpen())
            <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
          @elseif ($discussion->group->isClosed())
            <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
          @else
            <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
          @endif
          {{ $discussion->group->name }}
        </span>
      </a>
      <small>{{ $discussion->updated_at->diffForHumans() }}</small>
    </div>
  </div>


</div>
