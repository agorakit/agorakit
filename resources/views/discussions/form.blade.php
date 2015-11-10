<div class="form-group">
	{!! Form::label('name', 'Titre:') !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', 'Text:') !!}
	{!! Form::textarea('body', null, ['class' => 'form-control', 'required']) !!}
</div>

@section('footer')
<!-- CKeditor -->
<!--
<script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>

<script>
CKEDITOR.replace('body', {
	language: '{{App::getLocale()}}',
});
</script>
-->
@endsection
