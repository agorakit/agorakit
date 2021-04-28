<div class="text-center mb-5 mx-4" up-expand>
  <img src="{{route('users.cover', [$user, 'medium'])}}" class="rounded-full h-12 w-12 image-cover shrink-0" />
  <div>

    <a up-follow href="{{ route('users.show', [$user]) }}">
      {{ $user->name }}
    </a>

  </div>
</div>