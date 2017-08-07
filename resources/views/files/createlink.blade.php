@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.create_link')}}</h1>

        <p>{{trans('messages.create_link_help')}}</p>


        {!! Form::open(['url' => action('FileController@createLink', $group->id)]) !!}


        <div class="form-group">

            <label for="title">{{trans('messages.title')}}</label>
            <input class="form-control" name="title" type="text"/>

            <label for="link">{{trans('messages.link')}}</label>
            <input class="form-control" name="link" type="text"/>

            <label for="tags">{{trans('messages.tags')}}</label>
            <input class="form-control" name="tags" type="text"/>
            <span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
        </div>

        <input class="btn btn-default" type="submit" name="{{trans('messages.create')}}"/>

        {!! Form::close() !!}


    </div>

@endsection
