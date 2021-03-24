<div class="py-3 border-gray-300 border-b flex @if ($file->isArchived()) status-archived @endif  @if ($file->isPinned()) status-pinned @endif"
    up-expand>

    <div class="relative">
        @if ($file->isPinned())
        <div
            class="text-xs absolute right-0 w-6 h-6 rounded-full text-white bg-blue-700 flex items-center justify-center border-white border-2 shadow-md ">
            <i class="fas fa-thumbtack" title="{{__('Pinned')}}"></i>
        </div>
        @endif

        @if ($file->isLink())
        <a class="mr-4 flex-shrink-0" href="{{ route('groups.files.download', [$file->group, $file]) }}"
            target="_blank">
            <img class="rounded w-12 h-12" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" />
        </a>
        @elseif ($file->isFolder())
        <a up-follow up-target=".files" class="mr-4 flex-shrink-0"
            href="{{ route('groups.files.index', ['group' => $file->group, 'parent' => $file]) }}">
            <img class="rounded w-12 h-12" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" />
        </a>
        @else
        <a up-follow up-target=".files" class="mr-4 flex-shrink-0" up-follow
            href="{{ route('groups.files.show', [$file->group, $file]) }}">
            <img class="rounded w-12 h-12" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" />
        </a>
        @endif
    </div>


    <div class="w-100 flex-grow">
        <div class="text-lg">
            @if ($file->isFolder())
            <a up-follow href="{{ route('groups.files.index', ['group' => $file->group, 'parent' => $file]) }}">
                @else
                <a up-follow href="{{ route('groups.files.show', [$file->group, $file]) }}">
                    @endif
                    {{ $file->name }}
                </a>
        </div>







        <div class="text-xs text-gray-500 flex space-x-2 hover:text-gray-600">

            @if ($file->isPinned())
            <div>
                <i class="fas fa-thumbtack"></i>
                {{__('Pinned')}}
            </div>
            @endif
            @if ($file->isArchived())
            <div>
                <i class="fa fa-archive"></i>
                {{__('Archived')}}
            </div>
            @endif

            <div>

                @if ($file->group->isOpen())
                <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                @elseif ($file->group->isClosed())
                <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                @else
                <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                @endif
                {{ $file->group->name }}

            </div>

            <div>

                <i class="fa fa-user-circle"></i> {{ $file->user->name }}

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

            @if ($file->getSelectedTags()->count() > 0)
            <div class="">
                <i class="fa fa-tags"></i>
                @foreach ($file->getSelectedTags() as $tag)
                @include('tags.tag')
                @endforeach
            </div>
            @endif

        </div>



    </div>

    @include('files.dropdown')


</div>