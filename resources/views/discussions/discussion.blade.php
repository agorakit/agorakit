<div up-follow up-expand up-reveal="false" class="flex items-start py-3 hover:bg-gray-100 border-b border-gray-300">

<div class="relative">
            @if ($discussion->isPinned())
                    <div class="text-xs absolute right-0 w-6 h-6 rounded-full text-white bg-blue-700 flex items-center justify-center border-white border-2 shadow-md ">
                        <i class="fas fa-thumbtack" title="{{__('Pinned')}}"></i>
                    </div>
            @endif
        @if ($discussion->user)
        <img class="h-8 w-8 sm:h-12 sm:w-12 rounded-full object-cover mx-1  flex-shrink-0"
            src="{{ route('users.cover', [$discussion->user, 'small']) }}" />
        @endif
    </div>

    <div class="mx-2 min-w-0 flex-grow">


        <div class="text-gray-900 text-lg truncate min-w-0">
        <a 
            href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
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
                {{ $discussion->group->name }}
                {{ $discussion->updated_at->diffForHumans() }}
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


    @can('update', $discussion)

    <div class="dropdown ml-4">
        <a class="rounded-full hover:bg-gray-400 w-10 h-10 flex items-center justify-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

            @can('update', $discussion)
            <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$discussion->group, $discussion]) }}">
                <i class="fa fa-pencil"></i>
                {{ trans('messages.edit') }}
            </a>
            @endcan


            @can('delete', $discussion)
            <a up-modal=".dialog" class="dropdown-item"
                href="{{ route('groups.discussions.deleteconfirm', [$discussion->group, $discussion]) }}">
                <i class="fa fa-trash"></i>
                {{ trans('messages.delete') }}
            </a>
            @endcan

            @can('pin', $discussion)
            <a class="dropdown-item" up-follow up-cache="false" up-restore-scroll="true"
                href="{{ route('groups.discussions.pin', [$discussion->group, $discussion]) }}">
                <i class="fa fa-thumbtack"></i>
                @if($discussion->isPinned())
                {{ trans('messages.unpin') }}
                @else
                {{ trans('messages.pin') }}
                @endif
            </a>
            @endcan

            @can('archive', $discussion)
            <a class="dropdown-item" up-follow up-cache="false" up-restore-scroll="true"
                href="{{ route('groups.discussions.archive', [$discussion->group, $discussion]) }}">
                <i class="fa fa-archive"></i>
                @if($discussion->isArchived())
                {{ trans('messages.unarchive') }}
                @else
                {{ trans('messages.archive') }}
                @endif
            </a>
            @endcan


            <a class="dropdown-item"
                href="{{ route('groups.discussions.history', [$discussion->group, $discussion]) }}"><i
                    class="fa fa-history"></i> {{ trans('messages.show_history') }}</a>

        </div>

    </div>
    @endcan


</div>