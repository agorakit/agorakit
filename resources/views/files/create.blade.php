@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.upload_files')}}</h1>


        {!! Form::open(['url' => action('FileController@create', $group->id), 'files'=>true]) !!}
        <div class="form-group">
            <label class="btn btn-default" for="file">{{trans('messages.select_one_or_more_files')}}
                <input name="files[]" id="file" type="file" multiple="mutiple" style="display: none !important;">
            </label>
        </div>

        <div class="form-group">
            <label for="tags">{{trans('messages.tags')}}</label>
            <input class="form-control" name="tags" type="text"/>
            <span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
        </div>

        <input class="btn btn-default" type="submit" name="{{trans('messages.upload')}}"/>

        {!! Form::close() !!}


    </div>

@endsection
