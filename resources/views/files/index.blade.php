@extends('app')

@section('content')

    @auth
        <div>
            <div class="d-flex mb-2 gap-1 flex-wrap">
                @include('partials.tags_filter')
                @include('partials.sort_dropdown')

            @can('create-file', $group)
                <a class="btn btn-primary" href="{{ route('groups.files.create', ['group' => $group, 'parent' => $parent_id]) }}"
                    up-layer="new">
                    <i class="fa fa-file me-2"></i>
                    {{ trans('Upload file') }}
                </a>
            @endcan

            @can('create-file', $group)
                <a class="btn btn-primary" href="{{ route('groups.files.createlink', ['group' => $group, 'parent' => $parent_id]) }}"
                    up-layer="new">
                    <i class="fa fa-link me-2"></i>
                    {{ trans('Create Link') }}
                </a>
            @endcan

            @can('create-folder', $group)
                <a class="btn btn-primary" href="{{ route('groups.files.createfolder', ['group' => $group, 'parent' => $parent_id]) }}"
                    up-layer="new">
                    <i class="fa fa-folder me-2"></i>
                    {{ trans('Create a folder') }}
                </a>
            @endcan

                @can('create-folder', $group)
                    <a class="btn btn-primary"
                        href="{{ route('groups.files.createfolder', ['group' => $group, 'parent' => $parent_id]) }}" up-layer="new">
                        <i class="fa fa-folder me-2"></i>
                        {{ trans('messages.create_folder_button') }}
                    </a>
                @endcan

            </div>
        @endauth

        <div class="files">

            <a href="{{ route('groups.files.index', $group) }}" up-target=".files" up-history=true>
                <i class="fa fa-folder-open-o"></i>
                <span class="">{{ trans('Root') }}</span>
            </a>

                <a href="{{ route('groups.files.index', $group) }}" up-history=true up-target=".files">
                    <i class="fa fa-folder-open-o"></i>
                    <span class="">{{ trans('messages.root') }}</span>
                </a>

                @if ($breadcrumb)
                    @foreach ($breadcrumb as $my_parent)
                        <i class="fa fa-angle-right fill-current text-secondary"></i>
                        <a class=""
                            href="{{ route('groups.files.index', ['group' => $group, 'parent' => $my_parent->id]) }}"
                            up-history=true up-target=".files">
                            {{ $my_parent->name }}
                        </a>
                    @endforeach
                @endif

            </h3>

            <div class="list-group list-group-flush list-group-hoverable">
                @forelse($folders as $file)
                    @include('files.file')
                @empty
                @endforelse

                @forelse($files as $file)
                    @include('files.file')
                @empty
                @endforelse
                {{ $files->appends(request()->query())->links() }}
            </div>

        @endsection
