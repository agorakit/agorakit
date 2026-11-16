@extends('dialog')
@section('content')

  <h1>{{trans('Are you sure you want to delete?')}}</h1>

  <p>{{strip_tags($comment->body)}}</p>

  {!! Form::model($comment, array('method' => 'DELETE', 'action' => ['CommentController@destroy', $group, $discussion, $comment])) !!}


  <div class="mt-5 d-flex justify-content-between align-items-center">
    {!! Form::submit(trans('Delete'), ['class' => 'btn btn-danger']) !!}
  </div>

  {!! Form::close() !!}

@endsection
