<div class="w-32 bg-gray-200 shadow-md mr-3 mb-3 p-3 inline-block rounded">
    <a up-follow up-expand href="{{ route('groups.files.show', [$file->group, $file]) }}">

        <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" class="w-full">

        <div class="text-xs h-8 overflow-hidden">
            {{ $file->name }}
        </div>

        <div class="text-xs text-gray-600">

            <div class="h-4 overflow-hidden">
                <i class="fa fa-user-circle"></i> {{ $file->user->name }}
            </div>

            <div class="h-4 overflow-hidden">
                <i class="fa fa-clock-o"></i> {{ $file->updated_at }}

            </div>

            <div class="h-4 overflow-hidden">
                @if ($file->isFile())
                <i class="fa fa-database"></i> {{sizeForHumans($file->filesize)}}
                @endif
            </div>

            <div class="h-4 overflow-hidden">
                @if ($file->isLink())
                <i class="fas fa-link"></i> {{$file->path}}
                @endif
            </div>

        </div>

    </a>
</div>