@extends('app')

@section('content')

    @include('groups.tabs')

    @auth
        <div class="sm:flex justify-between">
            <div class="flex mb-2 space-x-1">
                @include('partials.tags_filter')
                @include('partials.sort_dropdown')
            </div>

            <div class="">
                @can('create-file', $group)
                    <a class="btn btn-primary" href="{{ route('groups.files.create', $group ) }}">
                        <i class="fa fa-file"></i>
                        {{trans('messages.create_file_button')}}
                    </a>
                @endcan

                @can('create-file', $group)
                    <a class="btn btn-primary" href="{{ route('groups.files.createlink', $group ) }}">
                        <i class="fa fa-link"></i>
                        {{trans('messages.create_link_button')}}
                    </a>
                @endcan

                @can('create-folder', $group)
                    <a class="btn btn-primary" href="{{ route('groups.files.createfolder', $group ) }}">
                        <i class="fa fa-folder"></i>
                        {{trans('messages.create_folder_button')}}
                    </a>
                @endcan
            </div>

        </div>
    @endauth



    <div class="files items mt-5">
        @forelse( $files as $file )
            @include('files.file')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse
        {{$files->appends(request()->query())->links()}}
    </div>


@endsection
