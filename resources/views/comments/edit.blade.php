@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('Edit a comment')}}</h1>


  {!! Form::model($comment, array('action' => ['CommentController@update', $group, $discussion, $comment], 'files' => true)) !!}

  @include('comments.form')

  <div class="form-group mt-4">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
