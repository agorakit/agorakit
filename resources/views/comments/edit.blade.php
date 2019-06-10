@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.modify')}}</h1>


  {!! Form::model($comment, array('action' => ['CommentController@update', $group, $discussion, $comment], 'files' => true)) !!}

  @include('comments.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
