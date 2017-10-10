@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">



        <h3>
            @if (isset($file))
                <a href="{{ route('groups.files.index', $group->id) }}"><i class="fa fa-home" aria-hidden="true"></i></a>
                {{$file->name}}
            @endif
        </h3>


        <div>
            <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}">
                <img src="{{ route('groups.files.preview', [$group->id, $file->id]) }}"/>
            </a>
        </div>

        <div>
            <ul>
                <li>{{trans('messages.author')}} : {{$file->user->name}}</li>
                <li>{{trans('messages.created')}} : {{$file->created_at}}</li>
                <li>{{trans('messages.tags')}} :
                    @foreach ($file->tags as $tag)
                        <span class="label label-default">{{$tag->name}}</span>
                    @endforeach
                </li>
            </ul>
        </div>

        <div>
            @if ($file->isLink())
                <a class="btn btn-primary" href="{{ route('groups.files.download', [$group->id, $file->id]) }}" target="_blank">
                    {{trans('messages.visit')}} {{$file->name}} ({{$file->path}})
                </a>
            @else
                <a class="btn btn-primary" href="{{ route('groups.files.download', [$group->id, $file->id]) }}">
                    {{trans('messages.download')}} {{$file->name}}
                </a>
            @endif
        </div>


    </div>

@endsection
