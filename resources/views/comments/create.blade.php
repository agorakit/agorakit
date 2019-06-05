{!! Form::open(array('action' => ['CommentController@store', $group, $discussion])) !!}

@include('comments.form')

<div class="form-group">
  {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary btn-lg']) !!}
</div>

{!! Form::close() !!}
