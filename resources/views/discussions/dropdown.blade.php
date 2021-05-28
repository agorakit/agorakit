{{-- this dropdown is shown on discussion show pages and on discussion summary pages --}}

<div class="ml-4 dropdown">
    <a class="rounded-full hover:bg-gray-400 w-10 h-10 flex items-center justify-center"
        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
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

        @can('delete', $discussion)
        <a up-modal=".dialog" class="dropdown-item"
            href="{{ route('groups.discussions.deleteconfirm', [$discussion->group, $discussion]) }}">
            <i class="fa fa-trash"></i>
            {{ trans('messages.delete') }}
        </a>
        @endcan

        @can('pin', $discussion)
        <a class="dropdown-item"
            href="{{ route('groups.discussions.pin', [$discussion->group, $discussion]) }}"
            up-target="#discussion-{{$discussion->id}}" up-cache="false" up-reveal="false">
            <i class="fa fa-thumbtack"></i>
            @if($discussion->isPinned())
            {{ trans('messages.unpin') }}
            @else
            {{ trans('messages.pin') }}
            @endif
        </a>
        @endcan

        @can('archive', $discussion)
        <a class="dropdown-item"
            href="{{ route('groups.discussions.archive', [$discussion->group, $discussion]) }}"
            up-target="#discussion-{{$discussion->id}}" up-cache="false" up-reveal="false">
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
                class="fa fa-history"></i>
            {{ trans('messages.show_history') }}</a>
        @endif
    </div>
</div>