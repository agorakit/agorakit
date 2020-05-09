<div class="file d-flex @if ($file->isArchived()) status-archived @endif  @if ($file->isPinned()) status-pinned @endif" up-expand>
    <div class="thumbnail">
        @if ($file->isLink())
            <a class="mr-1" href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                <img class="rounded" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @else
            <a up-follow href="{{ route('groups.files.show', [$file->group, $file]) }}">
                <img class="rounded" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @endif
    </div>

    <div class="content" style="width: 100%;">
        <div class="name">
            <a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                {{ $file->name }}
                <i class="fa fa-external-link"></i>
            </a>

            @if ($file->tags->count() > 0)
                @foreach ($file->tags as $tag)
                    @include('tags.tag')
                @endforeach
            @endif
        </div>

        <div class="small meta">
            <div>
                @if ($file->isPinned())
                    <div class="badge badge-primary" style="min-width: 2em; margin: 0 2px;">
                        <i class="fas fa-thumbtack"></i>
                        {{__('Pinned')}}
                    </div>
                @endif
                @if ($file->isArchived())
                    <div class="badge badge-secondary" style="min-width: 2em; margin: 0 2px;">
                        <i class="fa fa-archive"></i>
                        {{__('Archived')}}
                    </div>
                @endif
            </div>
            <div>
                <a up-follow href="{{ route('groups.show', [$file->group_id]) }}">
                    @if ($file->group->isOpen())
                        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                    @elseif ($file->group->isClosed())
                        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                    @else
                        <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                    @endif
                    {{ $file->group->name }}
                </a>
            </div>

            <div>
                <a up-follow href="{{ route('users.show', [$file->user]) }}">
                    <i class="fa fa-user-circle"></i> {{ $file->user->name }}
                </a>
            </div>

            <div>
                <i class="fa fa-clock-o"></i> {{ $file->updated_at }}
            </div>

            <div>
                @if ($file->isFile())
                    <i class="fa fa-database"></i> {{sizeForHumans($file->filesize)}}
                @endif
            </div>
            <div>
                @if ($file->isLink())
                    <i class="fas fa-link"></i> {{$file->path}}
                @endif
            </div>
        </div>

    </div>

    @can('update', $file)
        <div class="ml-auto dropdown">
            <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                @can('update', $file)
                    <a class="dropdown-item" href="{{ route('groups.files.edit', [$file->group, $file]) }}">
                        <i class="fa fa-pencil"></i>
                        {{__('Rename')}}
                    </a>
                @endcan

                @can('delete', $file)
                    <a class="dropdown-item" href="{{ route('groups.files.deleteconfirm', [$file->group, $file]) }}">
                        <i class="fa fa-trash"></i>
                        {{trans('messages.delete')}}
                    </a>
                @endcan

                @can('pin', $file)
                    <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item" href="{{ route('groups.files.pin', [$file->group, $file]) }}">
                        <i class="fa fa-thumbtack"></i>
                        @if($file->isPinned())
                            {{trans('messages.unpin')}}
                        @else
                            {{trans('messages.pin')}}
                        @endif
                    </a>
                @endcan

                @can('archive', $file)
                    <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item" href="{{ route('groups.files.archive', [$file->group, $file]) }}">
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
    @endcan


</div>
