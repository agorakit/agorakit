<div class="file d-flex" up-expand>
    <div class="thumbnail">
        @if ($file->isLink())
            <a class="mr-1" href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                <i class="fa fa-link" aria-hidden="true" style="font-size:2.5rem; color: black"></i>
            </a>
        @else
            <a href="{{ route('groups.files.show', [$file->group, $file]) }}">
                <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @endif
    </div>

    <div class="content">
        <div class="name">
            @if ($file->isLink())
                <a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                    {{ $file->name }}
                    <i class="fa fa-external-link"></i>
                </a>
            @else
                <a  href="{{ route('groups.files.show', [$file->group, $file]) }}">{{ $file->name }}</a>
            @endif

            @if ($file->tags->count() > 0)
                @foreach ($file->tags as $tag)
                    <span class="badge tag">{{$tag->name}}</span>
                @endforeach
            @endif
        </div>

        <div class="small meta">
            <div>
                <a href="{{ route('groups.show', [$file->group_id]) }}">
                    @if ( $file->group->isPublic())
                        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                    @else
                        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                    @endif
                    {{ $file->group->name }}
                </a>
            </div>

            <div>
                <a href="{{ route('users.show', [$file->user->id]) }}">
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
        </div>

    </div>


    <div class="ml-auto dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-wrench" aria-hidden="true"></i>

        </button>

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
        </div>
    </div>


</div>
