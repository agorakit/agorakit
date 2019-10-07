@extends('app')

@section('content')

  @include('groups.tabs')


  <div class="tab_content">

    <h1>{{trans('Add a tag')}}</h1>

    
    {!! Form::model($tag, array('action' => ['GroupTagController@store', $group, $tag])) !!}

    @include('tags.form')

    <div class="form-group">
      {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary  btn-lg']) !!}
    </div>

    {!! Form::close() !!}

  </div>

@endsection
