{{--
This code could be used to leverage unpoly for ajax insert of new comments :
{!! Form::open(array('action' => ['CommentController@store', $group, $discussion], 'files'=>true, 'up-target' => '.comments', 'up-reveal' => 'true', 'up-restore-scroll' => 'true')) !!}
--}}

{!! Form::open(array('action' => ['CommentController@store', $group, $discussion], 'files'=>true, 'up-target' => '.comments', 'up-reveal' => '#unread', 'up-restore-scroll' => 'true')) !!}

@include('comments.form')

{!! Form::close() !!}
