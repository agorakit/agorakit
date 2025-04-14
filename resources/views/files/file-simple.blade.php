<div class="file d-flex" up-expand style="overflow: hidden;">
    <div class="thumbnail">
        @if ($file->isLink())
            <a class="mr-1" href="{{ route('files.download', [$file->group, $file]) }}" target="_blank">
                <img alt="{{ $file->name }}" src="{{ route('files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @else
            <a  href="{{ route('files.show', [$file->group, $file]) }}">
                <img alt="{{ $file->name }}" src="{{ route('files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @endif
    </div>

    <div class="content">
        <div class="name">
            <a href="{{ route('files.download', [$file->group, $file]) }}" target="_blank">
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

            <div class="d-flex">
                <div class="me-2">
                    <a  href="{{ route('groups.show', [$file->group_id]) }}">
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
                    <a  href="{{ route('users.show', [$file->user]) }}">
                        <i class="fa fa-user-circle"></i> {{ $file->user->name }}
                    </a>
                </div>
            </div>

            <div class="d-flex">
                <div class="me-2">
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

    </div>


</div>
