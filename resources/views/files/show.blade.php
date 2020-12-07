@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="flex justify-between">
        <h3>
            {{$file->name}}
        </h3>

        @can('update', $file)
            <div class="ml-auto dropdown">
                <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                    @can('update', $file)
                        <a class="dropdown-item" href="{{ route('groups.files.edit', [$file->group, $file]) }}">
                            <i class="fa fa-pencil"></i>
                            {{__('Rename')}}
                        </a>
                    @endcan


                    @can('delete', $file)
                        <a class="dropdown-item" href="{{ route('groups.files.deleteconfirm', [$file->group, $file]) }}">
                            <i class="fa fa-trash"></i>
                            {{trans('messages.delete')}}
                        </a>
                    @endcan

                    @can('pin', $file)
                        <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item" href="{{ route('groups.files.pin', [$file->group, $file]) }}">
                            <i class="fa fa-thumbtack"></i>
                            @if($file->isPinned())
                                {{trans('messages.unpin')}}
                            @else
                                {{trans('messages.pin')}}
                            @endif
                        </a>
                    @endcan

                    @can('archive', $file)
                        <a up-follow up-cache="false" up-restore-scroll="true" class="dropdown-item" href="{{ route('groups.files.archive', [$file->group, $file]) }}">
                            <i class="fa fa-archive"></i>
                            @if($file->isArchived())
                                {{trans('messages.unarchive')}}
                            @else
                                {{trans('messages.archive')}}
                            @endif
                        </a>
                    @endcan

                </div>
            </div>
        @endcan
    </div>


    <div class="sm:flex">

        <div class="w:-1/2 sm:w-1/4 mr-4">
            <a href="{{ route('groups.files.download', [$group, $file]) }}">
                <img src="{{ route('groups.files.preview', [$group, $file]) }}" class="responsive"/>
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
                    <br/>
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
                        {{__('Rename')}}
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
