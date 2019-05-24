<div class="user">
  <div class="avatar">
    <a href="{{ route('users.show', $user) }}">
      <img src="{{route('users.cover', [$user, 'small'])}}" class="rounded-circle" />
    </a>
  </div>
  <div class="content w-100">

    <div class="d-flex">
      <div class="name mr-2">
        <a href="{{ route('users.show', $user) }}">
          {{ $user->name }}
        </a>
      </div>


      <div class="tags">
        @if ($user->tags->count() > 0)
          @foreach ($user->tags as $tag)
            <a href="{{route('tags.show', $tag)}}" class="badge badge-primary">{{$tag->name}}</a>
          @endforeach
        @endif
      </div>
    </div>



    <span class="summary">{{summary($user->body) }}</span>

    <br />

    <div class="d-flex justify-content-between">
      <div class="groups">
        @foreach ($user->groups as $group)
          @unless ($group->isSecret())
            <a href="{{ route('groups.show', [$group]) }}" class="badge badge-secondary">

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
      <small>{{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}</small>
    </div>
  </div>
</div>
