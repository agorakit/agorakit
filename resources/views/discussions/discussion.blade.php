<div up-expand class="discussion @if ($discussion->unReadCount() > 0) unread @endif">



  <div class="avatar">
    <img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/>
  </div>

  <div class="content w-100">

    <div class="d-flex">
      <div class="name mr-2">
        <a up-target=".main" up-reveal="false" href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
          {{ $discussion->name }}
        </a>
      </div>
    </div>
    <div class="tags">
      @if ($discussion->tags->count() > 0)
        @foreach ($discussion->tags as $tag)
          @include('tags.tag')
        @endforeach
      @endif
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
        <div class="badge badge-danger" style="min-width: 2em">{{ $discussion->unReadCount() }} {{__('New')}}</div>
      </div>
    @else
      @if ($discussion->comments_count > 0)
        <div class="d-flex align-items-start">
          <div class="badge badge-secondary" style="min-width: 2em">{{ $discussion->comments_count }}</div>
        </div>
      @endif
    @endif
  </div>
</div>
</a>
