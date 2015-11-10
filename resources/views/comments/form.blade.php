{!! Form::open(array('action' => ['CommentController@reply', $group->id, $discussion->id])) !!}

<div class="form-group">
			{!! Form::textarea('body', null, ['class' => 'form-control' , 'required']) !!}
</div>

<div class="form-group">
{!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}
