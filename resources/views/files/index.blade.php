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
                    <a class="btn btn-primary" href="{{ route('groups.files.create', ['group' => $group, 'parent' =>  $parent] ) }}">
                        <i class="fa fa-file"></i>
                        {{trans('messages.create_file_button')}}
                    </a>
                @endcan

                @can('create-file', $group)
                    <a class="btn btn-primary" href="{{ route('groups.files.createlink', ['group' => $group, 'parent' =>  $parent] ) }}">
                        <i class="fa fa-link"></i>
                        {{trans('messages.create_link_button')}}
                    </a>
                @endcan

                @can('create-folder', $group)
                    <a class="btn btn-primary" href="{{ route('groups.files.createfolder', ['group' => $group, 'parent' =>  $parent] ) }}">
                        <i class="fa fa-folder"></i>
                        {{trans('messages.create_folder_button')}}
                    </a>
                @endcan
            </div>

        </div>
    @endauth


    @if ($breadcrumb)

    <a up-follow href="{{ route('groups.files.index', $group ) }}">
    Home
    </a>
        @foreach ($breadcrumb as $my_parent)
        > 
        <a up-follow href="{{ route('groups.files.index', ['group' => $group, 'parent' =>  $my_parent->id]) }}">
        {{$my_parent->name}} 
        </a>
        @endforeach

    @endif

    <div class="files items mt-5">
        @forelse( $files as $file )
            @include('files.file')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse
        {{$files->appends(request()->query())->links()}}
    </div>


@endsection
