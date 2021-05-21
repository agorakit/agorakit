<div up-follow up-expand up-reveal="false"
    class="flex relative items-start py-3 hover:bg-gray-100 border-b border-gray-300">

    @if ($discussion->isPinned())
    <div
        class="text-xs absolute left-0 w-6 h-6 rounded-full text-white bg-blue-700 flex items-center justify-center border-white border-2 shadow-md ">
        <i class="fas fa-thumbtack" title="{{__('Pinned')}}"></i>
    </div>
    @endif

    @if ($discussion->user)
    @include('users.avatar', ['user' => $discussion->user])
    @endif


    <div class="mx-2 min-w-0 flex-grow">


        <div class="text-gray-900 text-lg truncate min-w-0">
            <a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                @if($discussion->isArchived())
                [{{ __('Archived') }}]
                @endif
                {{ summary($discussion->name) }}
            </a>
        </div>





        <div class="text-gray-600 text-xs">
            @if ($discussion->user)
            {{ trans('messages.started_by') }}
            {{ $discussion->user->name }}
            @endif
            {{ trans('messages.in') }}
            {{ $discussion->group->name }},
            {{ dateForHumans($discussion->updated_at) }}
        </div>


        <div class="text-gray-600 truncate min-w-0 h-6">
            {{ summary($discussion->body) }}
        </div>

        @if($discussion->getSelectedTags()->count() > 0)
        <div class="text-gray-600 text-xs overflow-hidden h-5">
            @foreach($discussion->getSelectedTags() as $tag)
            @include('tags.tag')
            @endforeach
        </div>
        @endif

       
    </div>

    @if($discussion->unReadCount() > 0)
    <div class="rounded-full bg-red-700 text-xs text-red-100 justify-center px-2 py-1 flex flex-shrink-0">
        {{ $discussion->unReadCount() }} {{trans('messages.new')}}
    </div>
    @else
    @if($discussion->comments_count > 0)
    <div class="rounded-full bg-gray-700 text-xs text-gray-100 px-2 py-1 justify-center flex flex-shrink-0">
        {{ $discussion->comments_count }}
    </div>
    @endif
    @endif



    @include('discussions.dropdown')



</div>