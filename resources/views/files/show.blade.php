@extends('app')

@section('content')

    @include('groups.tabs')

    <h3>
        {{$file->name}}
    </h3>


    <div class="row">

        <div class="col-3">
            <a href="{{ route('groups.files.download', [$group, $file]) }}">
                <img src="{{ route('groups.files.preview', [$group, $file]) }}" class="img-fluid"/>
            </a>
        </div>

        <div class="col">
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
