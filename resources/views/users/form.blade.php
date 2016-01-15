<div class="form-group">
	{!! Form::label('name', 'Nom:') !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
	{!! Form::label('email', 'Email:') !!}
	{!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', 'Bio:') !!}
	{!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
		{!! Form::label('cover', 'Photo:') !!}
		{!! Form::file('cover', null, ['class' => 'form-control']) !!}
</div>


@include('partials.wysiwyg')
