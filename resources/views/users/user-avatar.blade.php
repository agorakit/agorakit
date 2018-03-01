
    <a href="{{ route('users.show', [$user]) }}">
            <div class="avatar" title="{{$user->name}}"><img src="{{$user->avatar()}}" class="rounded-circle"/></div>
    </a>
