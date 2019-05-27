<div class="discussion p-2 my-4 @if ($discussion->unReadCount() > 0) unread @endif" style="border: 1px solid #aaa; border-radius: 5px">

  {{--
  <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
  <div class="avatar">
  <img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/>
</div>
</a>
--}}

<div class="content w-100">

  <div class="d-flex">
    <div class="name mr-2">
      <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
        {{ $discussion->name }}
      </a>
    </div>
    <div class="tags">
      @if ($discussion->tags->count() > 0)
        @foreach ($discussion->tags as $tag)
          <a href="{{ action('TagController@show', $tag) }}"><span class="badge badge-primary">{{$tag->name}}</span></a>
        @endforeach
      @endif
    </div>
  </div>

  <div class="summary">
    {{summary($discussion->body) }}
  </div>

  <div class="meta">
    {{trans('messages.started_by')}}
    <strong>
      <a href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name}}</a>
    </strong>
    {{trans('messages.in')}}
    <strong>
      <a href="{{ route('groups.show', [$discussion->group]) }}">{{ $discussion->group->name}}</a>
    </strong>
    {{ $discussion->updated_at->diffForHumans()}}
  </div>
</div>



<div class="comment-count d-flex justify-content-end">
  @if ($discussion->unReadCount() > 0)
    <div class="d-flex align-items-start">
      <div class="badge badge-danger mr-1">{{__('New')}}</div>
      <div class="badge badge-primary" style="min-width: 2em">{{ $discussion->unReadCount() }}</div>
    </div>
  @else
    <div class="d-flex align-items-start">
      <div class="badge badge-secondary" style="min-width: 2em">{{ $discussion->comments_count }}</div>
    </div>
  @endif
</div>
</div>
</a>
