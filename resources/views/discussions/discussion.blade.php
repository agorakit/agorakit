<agorakit-discussion up-follow up-expand up-reveal="false"
    class="flex items-center  px-4 py-3 hover:bg-gray-100">

    <img class="h-12 w-12 rounded-full object-cover mx-1"
        src="{{ route('users.cover', [$discussion->user, 'small']) }}" />

    <div class="mx-2 flex-grow">
        <div class="text-gray-900 text-sm sm:text-base  h-5 overflow-hidden">
            <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
            {{ summary($discussion->name) }}
            </a>
        </div>


        @if ($discussion->tags->count() > 0)
            <div class="text-gray-600 text-xs overflow-hidden flex flex-no-wrap space-x-1 my-1">
                @foreach ($discussion->tags as $tag)
                    <span class="block bg-gray-400 text-gray-100 rounded px-1 sm:-py-1 flex-shrink-0">{{ $tag }}</span>
                @endforeach
            </div>
        @endif


        <div class="text-gray-600 text-xs h-5 overflow-hidden">
            {{ trans('messages.started_by') }}
            <a href="">{{ $discussion->user->name }}</a>
            {{ trans('messages.in') }}
            {{ $discussion->group->name }}
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
</agorakit-discussion>
