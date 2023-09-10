@extends('group')

@section('content')

@auth
<div class="sm:flex justify-between">
    <div class="flex mb-2 space-x-1">
        @include('partials.tags_filter')
        @include('partials.sort_dropdown')
    </div>

    <div class="">
        @can('create-file', $group)
        <a up-modal=".dialog" class="btn btn-primary"
            href="{{ route('groups.files.create', ['group' => $group, 'parent' =>  $parent] ) }}">
            <i class="fa fa-file me-2"></i>
            {{trans('messages.create_file_button')}}
        </a>
        @endcan

        @can('create-file', $group)
        <a up-modal=".dialog" class="btn btn-primary"
            href="{{ route('groups.files.createlink', ['group' => $group, 'parent' =>  $parent] ) }}">
            <i class="fa fa-link me-2"></i>
            {{trans('messages.create_link_button')}}
        </a>
        @endcan

        @can('create-folder', $group)
        <a up-modal=".dialog" class="btn btn-primary"
            href="{{ route('groups.files.createfolder', ['group' => $group, 'parent' =>  $parent] ) }}">
            <i class="fa fa-folder me-2"></i>
            {{trans('messages.create_folder_button')}}
        </a>
        @endcan
    </div>

</div>
@endauth

<div class="files">


<h3 class="my-5 text-2xl text-gray-800">

    <a up-follow up-target=".files" href="{{ route('groups.files.index', $group ) }}">
        <i class="fa fa-folder-open-o"></i>
        <span class="">{{trans('messages.root')}}</span>
    </a>

    @if ($breadcrumb)
    @foreach ($breadcrumb as $my_parent)
    <i class="fa fa-angle-right fill-current text-gray-600"></i>
    <a up-follow up-target=".files" class="" href="{{ route('groups.files.index', ['group' => $group, 'parent' =>  $my_parent->id]) }}">
        {{$my_parent->name}}
    </a>
    @endforeach
    @endif

</h3>

<div class="items mt-2">
    @forelse( $folders as $file )
    @include('files.file')
    @empty
    @endforelse

    @forelse( $files as $file )
    @include('files.file')
    @empty
    @endforelse
    {{$files->appends(request()->query())->links()}}
</div>


@endsection