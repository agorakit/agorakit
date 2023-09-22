<div class="user d-flex mb-4 pb-4 border-bottom" up-expand>

    <div class="me-3">
        <a href="{{ route('users.show', $user) }}">
            @include ('users.avatar', ['user' => $user])
        </a>
    </div>

    <div>
        <div>
            <a class="no-underline font-bold text-gray-800" href="{{ route('users.show', $user) }}">
                {{ $user->name }}
            </a>
        </div>

        <div class="text-meta">
            {{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}
        </div>

        <div class="mb-2">{{ summary($user->body) }}</div>

         @if ($user->tags->count() > 0)
            <div class="d-flex flex-wrap gap-1 mb-1">
                @foreach ($user->tags as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

        <div class="d-flex flex-wrap gap-1 mb-2">
            @foreach ($user->groups as $group)
                @unless ($group->isSecret())
                    <a class="tag" href="{{ route('groups.show', [$group]) }}">

                        @if ($group->isOpen())
                            <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                        @elseif ($group->isClosed())
                            <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                        @endif
                        {{ $group->name }}

                    </a>
                @endunless
            @endforeach
        </div>

    </div>
</div>
