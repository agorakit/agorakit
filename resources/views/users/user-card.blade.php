<div class="user-simple text-center mb-5" style="width: 10rem;" up-expand>
  <div class="avatar medium">
    <img src="{{route('users.cover', [$user, 'medium'])}}" class="rounded-circle"/>
  </div>
  <div>
    <h3 class="mt-2">
      <a up-follow href="{{ route('users.show', [$user]) }}">
        {{ $user->name }}
      </a>
    </h3>
    <div class="meta">
      {{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}
    </div>
  </div>
</div>
