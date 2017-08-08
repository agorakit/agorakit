@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">



        <h3>
            @if (isset($file))
                <a href="{{ action('FileController@index', $group->id) }}"><i class="fa fa-home" aria-hidden="true"></i></a>
                {{$file->name}}
            @endif
        </h3>


        <div>
            <a href="{{ action('FileController@download', [$group->id, $file->id]) }}">
                <img src="{{ action('FileController@preview', [$group->id, $file->id]) }}"/>
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
            <a class="btn btn-primary" href="{{ action('FileController@download', [$group->id, $file->id]) }}">
                {{trans('messages.download')}} {{$file->name}}
            </a>
        </div>


    </div>

@endsection
