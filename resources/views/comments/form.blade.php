{!! Form::open(array('action' => ['CommentController@store', 'discussion', $discussion->id])) !!}

<div class="form-group">
			{!! Form::textarea('body', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit('Reply', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}
