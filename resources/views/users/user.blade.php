<div class="user">
    <div class="avatar"><img src="{{$user->avatar()}}" class="rounded-circle"/></div>
    <div class="content w-100">
        <a href="{{ route('users.show', [$user]) }}">
            <div class="d-flex justify-content-between align-items-center">
                <span class="name">
                    {{ $user->name }}
                </span>

            </div>
            <span class="summary">{{summary($user->body) }}</span>
        </a>
        <br/>

        <div class="d-flex justify-content-between">
            <div class="groups">
                @foreach ($user->groups as $group)
                    <a href="{{ route('groups.show', [$group]) }}">
                        <span class="badge badge-secondary badge-group">
                            @if ( $group->isPublic())
                                <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                            @else
                                <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                            @endif

                            {{ $group->name }}
                        </span>
                    </a>
                @endforeach
            </div>
            <small>{{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}</small>
        </div>
    </div>
</div>
