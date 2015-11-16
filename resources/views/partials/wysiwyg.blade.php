
@section('head')
{!! Html::style('/packages/summernote/summernote.css') !!}
@stop

@section('footer')
{!! Html::script('/packages/summernote/summernote.min.js') !!}

<script>
$(document).ready(function() {
  $("textarea[name='body']").summernote({
		styleTags: ['p', 'h1', 'h2', 'h3'],
		toolbar: [
    //[groupname, [button list]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['ul', 'ol']],
  ]
	});
});
</script>

@stop
