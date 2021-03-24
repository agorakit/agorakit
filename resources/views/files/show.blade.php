@extends('app')

@section('content')

@include('groups.tabs')




<div class="flex justify-between">


    <h3 class="my-5">

        <i class="fa fa-folder-open-o"></i>
        <a up-follow up-target=".files" href="{{ route('groups.files.index', $group ) }}">
            <span class="">{{trans('messages.root')}}</span>
        </a>

        @if (isset($breadcrumb))
        @foreach ($breadcrumb as $my_parent)
        <i class="fa fa-angle-right fill-current text-gray-600"></i>
        <a up-follow up-target=".files" class=""
            href="{{ route('groups.files.index', ['group' => $group, 'parent' =>  $my_parent->id]) }}">
            {{$my_parent->name}}
        </a>
        @endforeach
        @endif

        <i class="fa fa-angle-right fill-current text-gray-600"></i>

        {{$file->name}}

    </h3>


    @include('files.dropdown')
</div>


<div class="sm:flex">

    <div class="w:-1/2 sm:w-1/4 mr-4">
        <a href="{{ route('groups.files.download', [$group, $file]) }}">
            <img src="{{ route('groups.files.preview', [$group, $file]) }}" class="responsive" />
        </a>
    </div>

    <div class="sm:w-3/4">
        <div class="mb-4">
            <div>
                <a up-follow href="{{ route('users.show', [$file->user]) }}">
                    <i class="fa fa-user-circle"></i> {{ $file->user->name }}
                </a>
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


            @if ($file->tags->count() > 0)
            {{trans('messages.tags')}} :
            @foreach ($file->tags as $tag)
            @include('tags.tag')
            @endforeach
            <br />
            @endif

        </div>

        <div>
            @if ($file->isLink())
            <a class="btn btn-primary" href="{{ route('groups.files.download', [$group, $file]) }}" target="_blank">
                {{trans('messages.visit')}})
            </a>
            @else
            <a class="btn btn-primary" href="{{ route('groups.files.download', [$group, $file]) }}" target="_blank">
                {{trans('messages.download')}}
            </a>
            @endif


            @can('update', $file)
            <a class="btn btn-secondary" href="{{ route('groups.files.edit', [$group, $file]) }}">
                <i class="fa fa-pencil"></i>
                {{trans('messages.edit')}}
            </a>
            @endcan

            @can('delete', $file)
            <a class="btn btn-secondary" href="{{ route('groups.files.deleteconfirm', [$group, $file]) }}">
                <i class="fa fa-trash"></i>
                {{trans('messages.delete')}}
            </a>
            @endcan


        </div>
    </div>
</div>
@endsection