@extends('dialog')

@section('content')


    <h1>{{trans('Add a tag')}}</h1>


    {!! Form::model($tag, ['action' => ['GroupTagController@store', $group, $tag], 'up-target' =>'tab_content']) !!}

    @include('tags.form')

    <div class="form-group">
      {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary  btn-lg']) !!}
    </div>

    {!! Form::close() !!}


@endsection
