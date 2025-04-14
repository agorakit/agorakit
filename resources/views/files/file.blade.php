<div class="d-flex justify-content-between align-items-start mb-md-4 pb-md-4 mb-3 pb-3 border-bottom" up-expand>

    <div class="me-3">
        @if ($file->isLink())
            <a class="" href="{{ route('files.show', [$file->group, $file]) }}">
                <img alt="{{ $file->name }}" class="avatar" src="{{ route('files.thumbnail', [$file->group, $file]) }}" />
            </a>
        @elseif ($file->isFolder())
            <a class="" href="{{ route('files', ['group' => $file->group, 'parent' => $file]) }}"
                up-target=".files" up-history=true>
                <img alt="{{ $file->name }}" class="avatar" src="{{ route('files.thumbnail', [$file->group, $file]) }}" />
            </a>
        @else
            <a class="mr-4 flex-shrink-0" href="{{ route('files.show', [$file->group, $file]) }}"
                up-target=".files">
                <img alt="{{ $file->name }}" class="avatar" src="{{ route('files.thumbnail', [$file->group, $file]) }}" />
            </a>
        @endif
    </div>

    <div class="w-100 flex-grow">
        <div class="fw-bold">
            @if ($file->isLink())
                <a class="" href="{{ $file->path }}" target="_blank">
                    {{ $file->name }}
                </a>
            @elseif ($file->isFolder())
                <a href="{{ route('files', ['group' => $file->group, 'parent' => $file]) }}">
                    {{ $file->name }}
                </a>
            @else
                <a href="{{ route('files.show', [$file->group, $file]) }}">
                    {{ $file->name }}
                </a>
            @endif

        </div>

        <div class="text-secondary d-flex flex-wrap gap-2">

            @if ($file->isArchived())
                <div class="">
                    <i class="fa fa-archive"></i>
                    {{ __('Archived') }}
                </div>
            @endif

            <div class="me-2">
                @if ($file->group->isOpen())
                    <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                @elseif ($file->group->isClosed())
                    <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                @else
                    <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                @endif
                {{ $file->group->name }}
            </div>

            <div class="me-2">
                <i class="fa fa-user-circle"></i>
                @if (isset($file->user))
                    {{ $file->user->name }}
                @endif
            </div>

            <div class="me-2">
                <i class="fa fa-clock-o"></i> {{ $file->updated_at }}
            </div>

            <div>
                @if ($file->isFile())
                    <i class="fa fa-database"></i> {{ sizeForHumans($file->filesize) }}
                @endif
            </div>

        </div>

        @if ($file->isLink())
            <div class="me-2">
                <i class="fas fa-link"></i> <a href="{{ $file->path }}" target="_blank">{{ $file->path }}</a>
            </div>
        @endif

        @if ($file->getSelectedTags()->count() > 0)
            <div class="text-secondary mt-2">
                @foreach ($file->getSelectedTags() as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

    </div>

    @if ($file->isPinned())
        <i class="fas fa-thumbtack me-2" title="{{ __('Pinned') }}"></i>
    @endif

    @include('files.dropdown')

</div>
