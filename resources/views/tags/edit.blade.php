@extends('dialog')

@section('content')

    <h1>{{trans('messages.modify')}} @include('tags.tag') </h1>

    {!! Form::model($tag, ['action' => ['GroupTagController@update', $group, $tag], 'up-target' =>'tab_content']) !!}
    @include('tags.form')

    <div>
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
    

@endsection
