<div class="ml-auto dropdown">
    <a class="rounded-full hover:bg-gray-400 w-10 h-10 d-flex items-center justify-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-ellipsis-h"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

        @can('update', $file)
        <a class="dropdown-item" href="{{ route('groups.files.edit', [$file->group, $file]) }}">
            <i class="fa fa-pencil"></i>
            {{trans('messages.edit')}}
        </a>
        @endcan

        @can('delete', $file)
        <a class="dropdown-item" href="{{ route('groups.files.deleteconfirm', [$file->group, $file]) }}">
            <i class="fa fa-trash"></i>
            {{trans('messages.delete')}}
        </a>
        @endcan

        @can('pin', $file)
        <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item"
            href="{{ route('groups.files.pin', [$file->group, $file]) }}">
            <i class="fa fa-thumbtack"></i>
            @if($file->isPinned())
            {{trans('messages.unpin')}}
            @else
            {{trans('messages.pin')}}
            @endif
        </a>
        @endcan

        @can('archive', $file)
        <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item"
            href="{{ route('groups.files.archive', [$file->group, $file]) }}">
            <i class="fa fa-archive"></i>
            @if($file->isArchived())
            {{trans('messages.unarchive')}}
            @else
            {{trans('messages.archive')}}
            @endif
        </a>
        @endcan

    </div>
</div>