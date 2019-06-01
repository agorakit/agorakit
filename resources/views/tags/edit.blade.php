@extends('app')

@section('content')

  @include('groups.tabs')
  <div class="tab_content">
    <h1>{{trans('messages.modify')}} @include('tags.tag') </h1>

    {!! Form::model($tag, array('action' => ['GroupTagController@update', $group, $tag])) !!}
    @include('tags.form')

    <div class="form-group">
      {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
  </div>

@endsection
