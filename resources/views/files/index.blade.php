@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="toolbox">
        @can('create-file', $group)
            <a class="btn btn-primary" href="{{ route('groups.files.create', $group->id ) }}">
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
    </div>


    @include('partials.tags_filter')


    <div class="files mt-5">
        @forelse( $files as $file )
            @include('files.file')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse
    </div>


@endsection
