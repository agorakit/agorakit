
    <a up-follow href="{{ route('users.show', [$user]) }}">
            <div class="avatar" title="{{$user->name}}"><img src="{{route('users.cover', [$user, 'small'])}}" class="rounded-circle"/></div>
    </a>
