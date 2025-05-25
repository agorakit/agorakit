@extends('app')

@section('content')

    <div>
        <div class="d-flex justify-content-between">

            <h3>
                <i class="fa fa-folder-open-o"></i>
                <a up-target=".files" href="{{ route('groups.files.index', $group) }}">
                    <span class="">{{ trans('messages.root') }}</span>
                </a>

                @if (isset($breadcrumb))
                    @foreach ($breadcrumb as $my_parent)
                        <i class="fa fa-angle-right"></i>
                        <a up-target=".files" class=""
                            href="{{ route('groups.files.index', ['group' => $group, 'parent' => $my_parent->id]) }}">
                            {{ $my_parent->name }}
                        </a>
                    @endforeach
                @endif
                <i class="fa fa-angle-right"></i>
                {{ $file->name }}
            </h3>

            @include('files.dropdown')
        </div>

        <div class="file">

            @if ($file->isImage())
                <a href="{{ route('groups.files.download', [$group, $file]) }}" target="_blank">
                    <img alt="{{ $file->name }}" src="{{ route('groups.files.preview', [$group, $file]) }}" class="responsive rounded" />
                </a>
            @elseif ($file->isLink())
                <a href="{{ $file->path }}" target="_blank">
                    <img alt="{{ $file->name }}" src="{{ route('groups.files.thumbnail', [$group, $file]) }}" class="rounded"
                        style="width: 100px; height: 100px" />
                </a>
            @else
                <a href="{{ route('groups.files.download', [$group, $file]) }}" target="_blank">
                    <img alt="{{ $file->name }}" src="{{ route('groups.files.thumbnail', [$group, $file]) }}" class="rounded"
                        style="width: 100px; height: 100px" />
                </a>
            @endif

            <div>
                {{ $file->name }}
            </div>

            <div class="my-4 text-sm text-gray-700">
                <div>
                    <a href="{{ route('users.show', [$file->user]) }}">
                        <i class="fa fa-user-circle"></i> {{ $file->user->name }}
                    </a>
                </div>

                <div>
                    <i class="fa fa-clock-o"></i> {{ $file->updated_at }}
                </div>

                <div>
                    @if ($file->isFile())
                        <i class="fa fa-database"></i> {{ sizeForHumans($file->filesize) }}
                    @endif
                </div>
                <div>
                    @if ($file->isLink())
                        <i class="fas fa-link"></i> <a href="{{ $file->path }}" target="_blank">{{ $file->path }}</a>
                    @endif
                </div>

                @if ($file->tags->count() > 0)
                    {{ trans('messages.tags') }} :
                    @foreach ($file->tags as $tag)
                        @include('tags.tag')
                    @endforeach
                    <br />
                @endif

            </div>

            <div>
                @if ($file->isLink())
                    <a class="btn btn-primary" href="{{ route('groups.files.download', [$group, $file]) }}"
                        target="_blank">
                        {{ trans('messages.visit') }}
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ route('groups.files.download', [$group, $file]) }}"
                        target="_blank">
                        <i class="fa fa-download me-2"></i>
                        {{ trans('messages.download') }}
                    </a>
                @endif

                @can('update', $file)
                    <a class="btn btn-secondary" href="{{ route('groups.files.edit', [$group, $file]) }}">
                        <i class="fa fa-pencil me-2"></i>
                        {{ trans('messages.edit') }}
                    </a>
                @endcan

                @can('delete', $file)
                    <a class="btn btn-danger" href="{{ route('groups.files.deleteconfirm', [$group, $file]) }}">
                        <i class="fa fa-trash me-2"></i>
                        {{ trans('messages.delete') }}
                    </a>
                @endcan

            </div>
        </div>
    </div>
@endsection
