@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>{{trans('messages.delete_confirm_title')}}</h1>

  <p>{{strip_tags($comment->body)}}</p>

  {!! Form::model($comment, array('method' => 'DELETE', 'action' => ['CommentController@destroy', $group->id, $discussion->id, $comment->id])) !!}



  <div class="form-group">
              {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
