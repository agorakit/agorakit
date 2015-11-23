@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>{{trans('messages.modify')}}</h1>


  {!! Form::model($comment, array('action' => ['CommentController@update', $group->id, $discussion->id, $comment->id])) !!}

  @include('comments.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
