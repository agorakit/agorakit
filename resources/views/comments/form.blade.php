{!! Form::open(array('action' => ['CommentController@reply', $group->id, $discussion->id])) !!}

<div class="form-group">
			{!! Form::textarea('body', null, ['class' => 'form-control' , 'required']) !!}
</div>

<div class="form-group">
{!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}


@section('head')
{!! Html::style('/packages/summernote/summernote.css') !!}
@stop

@section('footer')
{!! Html::script('/packages/summernote/summernote.min.js') !!}

<script>
$(document).ready(function() {
  $("textarea[name='body']").summernote({
		toolbar: [
    //[groupname, [button list]]

    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
    ['para', ['ul', 'ol']],
  ]
	});
});
</script>

@stop
