<div class="user">
  
    <a up-follow href="{{ route('users.show', $user) }}">
      <img src="{{route('users.cover', [$user, 'small'])}}" class="rounded-full w-8 h-8 mr-4" />
    </a>

  <div class="w-full">
    <div class="flex">
      <div class="name mr-2">
        <a up-follow href="{{ route('users.show', $user) }}">
          {{ $user->name }}
        </a>
      </div>


      <div class="tags">
        @if ($user->tags->count() > 0)
          @foreach ($user->tags as $tag)
            @include('tags.tag')
          @endforeach
        @endif
      </div>
    </div>



    <div class="text-gray-700">{{summary($user->body) }}</div>



    <div class="mt-2">
        @foreach ($user->groups as $group)
          @unless ($group->isSecret())
            <a up-follow href="{{ route('groups.show', [$group]) }}" 
            class="inline-block bg-gray-300 text-gray-700 rounded-full text-xs px-2 py-1 mr-1 mb-1">

              @if ($group->isOpen())
                <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
              @elseif ($group->isClosed())
                <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
              @endif
              {{ $group->name }}

            </a>
          @endunless
        @endforeach
    </div>
    <div class="mt-2 text-sm text-gray-600">
    {{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}
    </div>
  </div>
</div>
