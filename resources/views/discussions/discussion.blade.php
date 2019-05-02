<div class="discussion @if ($discussion->unReadCount() > 0) unread @endif">

  <div class="avatar"><img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/></div>
  <div class="content w-100">

    <div class="d-flex justify-content-between align-items-start">
      <span class="name">
        <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
          {{ $discussion->name }}
        </a>
      </span>


      <div class="d-flex justify-content-right align-items-start">
        @if ($discussion->unReadCount() > 0)
          <div class="badge-unread">{{ $discussion->unReadCount() }}</div>
        @endif



        @can('update', $discussion)
          <div class="ml-4 dropdown">
            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cog" aria-hidden="true"></i>
            </button>


            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
              @can('update', $discussion)
                <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$discussion->group, $discussion]) }}">
                  <i class="fa fa-pencil"></i>
                  {{trans('messages.edit')}}
                </a>
              @endcan

              <a class="dropdown-item" up-modal=".dialog" href="{{ route('groups.tags.edit', [$discussion->group, 'discussions', $discussion]) }}">
                <i class="fa fa-tags"></i> Edit tags
              </a>

              @can('delete', $discussion)
                <a up-modal=".dialog" class="dropdown-item" href="{{ route('groups.discussions.deleteconfirm', [$discussion->group, $discussion]) }}">
                  <i class="fa fa-trash"></i>
                  {{trans('messages.delete')}}
                </a>
              @endcan

              @if ($discussion->revisionHistory->count() > 0)
                <a class="dropdown-item" href="{{route('groups.discussions.history', [$discussion->group, $discussion])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
              @endif
            </div>
          </div>
        @endcan

      </div>
    </div>

    <div class="tags">
      @if ($discussion->tags->count() > 0)
        @foreach ($discussion->tags as $tag)
          <span class="badge tag">{{$tag->name}}</span>
        @endforeach
      @endif
    </div>

    <span class="summary">
      <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
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
