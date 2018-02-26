<div class="discussion @if ($discussion->unReadCount() > 0) unread @endif">
    <div class="avatar"><img src="{{$discussion->user->avatar()}}" class="rounded-circle"/></div>
    <div class="content w-100">
        <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
            <div class="d-flex justify-content-between align-items-center">
                <span class="name">
                    {{ $discussion->name }}
                </span>
                @if ($discussion->unReadCount() > 0)
                    <span class="badge badge-secondary">{{ $discussion->unReadCount() }}</span>
                @endif
            </div>
            <span class="summary">{{summary($discussion->body) }}</span>
        </a>
        <br/>

        <div class="d-flex justify-content-between">
            <a href="{{ route('groups.show', [$discussion->group_id]) }}">
                <span class="badge badge-secondary badge-group">
                    @if ( $discussion->group->isPublic())
                        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                    @else
                        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                    @endif

                    {{ $discussion->group->name }}
                </span>
            </a>
            <small>u:{{ $discussion->updated_at->diffForHumans() }}</small>
            <small>c:{{ $discussion->created_at->diffForHumans() }}</small>
        </div>
    </div>
</div>
