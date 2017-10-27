<div class="form-group">
	{!! Form::label('name', trans('messages.title')) !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', trans('messages.body')) !!}
	{!! Form::textarea('body', null, ['class' => 'form-control wysiwyg', 'required']) !!}
</div>

@include('partials.tags_form')


@include('partials.wysiwyg')
