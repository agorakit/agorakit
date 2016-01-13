<div class="form-group">
	{!! Form::label('name', 'Titre:') !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', 'Text:') !!}
	{!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control', 'required']) !!}
</div>

@include('partials.wysiwyg')
