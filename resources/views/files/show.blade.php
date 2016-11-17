@extends('app')

@section('content')

    @include('partials.grouptab')

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



        <a href="{{ action('FileController@download', [$group->id, $file->id]) }}">
            <img src="{{ action('FileController@preview', [$group->id, $file->id]) }}"/>{{$file->name}}
        </a>


    </div>

@endsection
