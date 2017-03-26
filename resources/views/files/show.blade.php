@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">



        <h3>
            @if (isset($file))
                <a href="{{ action('FileController@index', $group->id) }}"><i class="fa fa-home" aria-hidden="true"></i></a>
                @foreach ($file->getAncestors() as $ancestor)
                    / <a href="{{ action('FileController@show', [$group->id, $ancestor->id]) }}">{{$ancestor->name}}</a>
                @endforeach
                / {{$file->name}}
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
            </ul>
        </div>

        <div>
            <a class="btn btn-primary" href="{{ action('FileController@download', [$group->id, $file->id]) }}">
                {{trans('messages.download')}} {{$file->name}}
            </a>
        </div>


    </div>

@endsection
