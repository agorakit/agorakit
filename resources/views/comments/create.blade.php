{{--
This code could be used to leverage unpoly for ajax insert of new comments :
{!! Form::open(array('action' => ['CommentController@store', $group, $discussion], 'files'=>true, 'up-target' => '.comments', 'up-reveal' => 'true', 'up-restore-scroll' => 'true')) !!}
--}}

{!! Form::open(array('action' => ['CommentController@store', $group, $discussion], 'files'=>true)) !!}

@include('comments.form')

<div class="form-group">
  {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary btn-lg']) !!}
</div>

{!! Form::close() !!}
