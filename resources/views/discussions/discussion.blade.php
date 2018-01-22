<div class="discussion @if ($discussion->unReadCount() > 0) unread @endif">
    <div class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></div>
    <div class="content">
        <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
            <span class="name">{{ $discussion->name }}</span>
            <span class="summary">{{summary($discussion->body) }}</span>
        </a>
        <br/>
        <a href="{{ route('groups.show', [$discussion->group_id]) }}">
            <span class="badge badge-primary">
                {{ $discussion->group->name }}
            </span>
        </a>
    </div>

    <div class="date">
        {{ $discussion->updated_at->diffForHumans() }}
    </div>

    <div class="unread">
        @if ($discussion->unReadCount() > 0)
            <span class="badge">{{ $discussion->unReadCount() }}</span>
        @endif
    </div>

</div>
