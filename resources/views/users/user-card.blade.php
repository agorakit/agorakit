<div class="mb-2 mr-2 flex p-2 bg-gray-200 shadow rounded-full items-center" up-expand>
  <img src="{{route('users.cover', [$user, 'medium'])}}" class="rounded-full h-8 w-8 image-cover shrink-0 mr-2" />
  <div class="text-sm text-gray-800 mr-2">

    <a class="no-underline" up-follow href="{{ route('users.show', [$user]) }}">
      {{ $user->name }}
    </a>

  </div>
</div>