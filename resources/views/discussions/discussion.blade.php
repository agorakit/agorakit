<div up-follow up-expand up-reveal="false" class="flex items-center  px-4 py-3 hover:bg-gray-100">

    <img class="h-12 w-12 rounded-full object-cover mx-1"
        src="{{ route('users.cover', [$discussion->user, 'small']) }}" />

    <div class="mx-2 flex-grow">
        <div class="text-gray-900 text-sm sm:text-base  h-5 overflow-hidden">
            <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                {{ summary($discussion->name) }}
            </a>
        </div>


        @if ($discussion->tags->count() > 0)
            <div class="text-gray-600 text-xs overflow-hidden space-x-1 my-1 h-5">
                @foreach ($discussion->tags as $tag)
                    <span class="inline-block bg-gray-400 text-gray-100 rounded px-1 sm:-py-1 mb-1">{{ $tag }}</span>
                @endforeach
            </div>
        @endif


        <div class="text-gray-600 text-xs h-5 overflow-hidden">
            {{ trans('messages.started_by') }}
            <a up-follow href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name}}</a>
            {{ trans('messages.in') }}
            <a up-follow href="{{ route('groups.show', [$discussion->group]) }}">{{ $discussion->group->name}}</a>
            {{ $discussion->updated_at->diffForHumans() }}
        </div>
    </div>

    @if ($discussion->unReadCount() > 0)
        <div class="rounded-full bg-red-700 text-xs text-red-200 w-6 justify-center flex flex-shrink-0">
            {{ $discussion->unReadCount() }} {{ __('New') }}
        </div>
    @else
        @if ($discussion->comments_count > 0)
            <div class="rounded-full bg-gray-700 text-xs text-gray-200 w-6 justify-center flex flex-shrink-0">
                {{ $discussion->comments_count }}
            </div>
        @endif
    @endif


    @can('update', $discussion)
        <a class="rounded-full bg-gray-200 text-gray-600 px-4 py-2 hover:bg-gray-400 ml-4 text-xs hidden sm:block flex-shrink-0" href="{{ route('groups.discussions.edit', [$discussion->group, $discussion]) }}">
            <i class="fa fa-pencil"></i>
            {{ trans('messages.edit') }}
        </a>
    @endcan

</div>
