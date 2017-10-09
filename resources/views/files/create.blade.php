@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.upload_files')}}</h1>


        {!! Form::open(['url' => route('groups.files.create', $group->id), 'files'=>true]) !!}
        <div class="form-group">
            <input name="files[]" id="file" type="file" multiple="mutiple" class="btn btn-primary" title="{{trans('messages.select_one_or_more_files')}}">
        </div>

        @include('partials.tags_form')

        <input class="btn btn-primary" type="submit" name="{{trans('messages.upload')}}"/>

        {!! Form::close() !!}
    </div>

@endsection

@include('partials.better-file-inputs')
