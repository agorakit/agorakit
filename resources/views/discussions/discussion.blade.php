<div up-follow up-expand up-reveal="false" class="flex items-center py-3 hover:bg-gray-100 border-b border-gray-300">

    <img class="h-12 w-12 rounded-full object-cover mx-1  flex-shrink-0"
        src="{{ route('users.cover', [$discussion->user, 'small']) }}" />

    <div class="mx-2 flex-grow">

        <div class="text-gray-900 text-sm sm:text-base">
            <a
                href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
                {{ summary($discussion->name) }}
            </a>
        </div>


        @if($discussion->tags->count() > 0)
            <div class="text-gray-600 text-xs overflow-hidden my-1 h-5">
                @foreach($discussion->tags as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif


        <div class="text-gray-600 text-xs">
            {{ trans('messages.started_by') }}
            {{ $discussion->user->name }}
            {{ trans('messages.in') }}
            {{ $discussion->group->name }}
            {{ $discussion->updated_at->diffForHumans() }}
        </div>
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
            <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                @can('update', $discussion)
                    <a class="dropdown-item"
                        href="{{ route('groups.discussions.edit', [$discussion->group, $discussion]) }}">
                        <i class="fa fa-pencil"></i>
                        {{ trans('messages.edit') }}
                    </a>
                @endcan

                @can('update', $discussion)
                    <a class="dropdown-item" up-modal=".dialog" up-closable="false"
                        href="{{ route('tagger.index', ['discussions', $discussion->id]) }}?r={{ rand(0,999999) }}">
                        <i class="fa fa-tag"></i>
                        {{ __('Edit tags') }}
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

                @if($discussion->revisionHistory->count() > 0)
                    <a class="dropdown-item"
                        href="{{ route('groups.discussions.history', [$discussion->group, $discussion]) }}"><i
                            class="fa fa-history"></i> {{ trans('messages.show_history') }}</a>
                @endif
            </div>

        </div>
    @endcan


</div>