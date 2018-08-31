@extends('dialog')
@section('content')

  <h1>{{trans('messages.delete_confirm_title')}}</h1>

  <p>{{strip_tags($comment->body)}}</p>

  {!! Form::model($comment, array('method' => 'DELETE', 'action' => ['CommentController@destroy', $group->id, $discussion->id, $comment->id])) !!}


  <div class="mt-5 d-flex justify-content-between align-items-center">
    {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
  </div>

  {!! Form::close() !!}

@endsection
