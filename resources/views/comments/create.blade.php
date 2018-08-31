{!! Form::open(array('action' => ['CommentController@store', $group, $discussion])) !!}

@include('comments.form')

<div class="form-group">
  {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary form-control']) !!}
</div>

{!! Form::close() !!}
