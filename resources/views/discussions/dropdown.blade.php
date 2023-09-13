{{-- this dropdown is shown on discussion show pages and on discussion summary pages --}}

<div class="ms-2 dropdown">
    <a class="btn btn-pills" id="dropdownMenuButton" data-bs-toggle="dropdown" type="button" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-ellipsis-h"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

        @can('update', $discussion)
            <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$discussion->group, $discussion]) }}">
                <i class="fa fa-pencil me-2"></i>
                {{ trans('messages.edit') }}
            </a>
        @endcan

        @can('delete', $discussion)
            <a class="dropdown-item" href="{{ route('groups.discussions.deleteconfirm', [$discussion->group, $discussion]) }}" up-layer="new">
                <i class="fa fa-trash me-2"></i>
                {{ trans('messages.delete') }}
            </a>
        @endcan

        @can('pin', $discussion)
            <a class="dropdown-item" href="{{ route('groups.discussions.pin', [$discussion->group, $discussion]) }}" up-target="#discussion-{{ $discussion->id }}" up-cache="false"
                up-reveal="false">
                <i class="fa fa-thumbtack me-2"></i>
                @if ($discussion->isPinned())
                    {{ trans('messages.unpin') }}
                @else
                    {{ trans('messages.pin') }}
                @endif
            </a>
        @endcan

        @can('archive', $discussion)
            <a class="dropdown-item" href="{{ route('groups.discussions.archive', [$discussion->group, $discussion]) }}" up-target="#discussion-{{ $discussion->id }}" up-cache="false"
                up-reveal="false">
                <i class="fa fa-archive me-2"></i>
                @if ($discussion->isArchived())
                    {{ trans('messages.unarchive') }}
                @else
                    {{ trans('messages.archive') }}
                @endif
            </a>
        @endcan

        @auth
            <a class="dropdown-item" href="{{ route('groups.discussions.history', [$discussion->group, $discussion]) }}"><i class="fa fa-history me-2"></i>
                {{ trans('messages.show_history') }}</a>
        @endauth
    </div>
</div>
