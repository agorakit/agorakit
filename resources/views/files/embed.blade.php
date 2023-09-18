<div class="mb-3 border p-2" up-expand>
    <div class="d-flex gap-2">
        <div>
            <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" alt="file cover"
                style="width: 3rem; height: 3rem" class="rounded">
        </div>
        <div>
            <div>
                <a up-layer="new" up-expand href="{{ route('groups.files.show', [$file->group, $file]) }}">
                    {{ $file->name }}
                </a>
            </div>
            <div class="text-secondary d-flex flex-wrap gap-2">

                <div class="">
                    <i class="fa fa-user-circle"></i> {{ $file->user->name }}
                </div>

                <div class="">
                    <i class="fa fa-clock-o"></i> {{ $file->updated_at }}

                </div>

                <div class="">
                    @if ($file->isFile())
                        <i class="fa fa-database"></i> {{ sizeForHumans($file->filesize) }}
                    @endif
                </div>

                <div class="">
                    @if ($file->isLink())
                        <i class="fas fa-link"></i> {{ $file->path }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>