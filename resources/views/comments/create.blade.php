{!! Form::open(['action' => ['CommentController@store', $group, $discussion], 'files' => true, 'up-target' =>
'.comments', 'up-reveal' => '#unread', 'up-restore-scroll' => 'true']) !!}

@include('comments.form')

<div class="form-group">
    {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary btn-lg']) !!}
</div>

{!! Form::close() !!}
