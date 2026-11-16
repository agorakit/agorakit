<div class="ml-auto dropdown">
    <a class="btn btn-pills" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-ellipsis-h"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">

        @can('update', $file)
        <a class="dropdown-item" href="{{ route('groups.files.edit', [$file->group, $file]) }}">
            <i class="fa fa-pencil me-2"></i>
            {{trans('Edit')}}
        </a>
        @endcan

        @can('delete', $file)
        <a class="dropdown-item" href="{{ route('groups.files.deleteconfirm', [$file->group, $file]) }}">
            <i class="fa fa-trash me-2"></i>
            {{trans('Delete')}}
        </a>
        @endcan

        @can('pin', $file)
        <a  up-cache="false" up-restore-scroll="true" class="dropdown-item"
            href="{{ route('groups.files.pin', [$file->group, $file]) }}">
            <i class="fa fa-thumbtack me-2"></i>
            @if($file->isPinned())
            {{trans('Unpin')}}
            @else
            {{trans('Pin')}}
            @endif
        </a>
        @endcan

        @can('archive', $file)
        <a  up-cache="false" up-restore-scroll="true" class="dropdown-item"
            href="{{ route('groups.files.archive', [$file->group, $file]) }}">
            <i class="fa fa-archive me-2"></i>
            @if($file->isArchived())
            {{trans('Unarchive')}}
            @else
            {{trans('Archive')}}
            @endif
        </a>
        @endcan

    </div>
</div>