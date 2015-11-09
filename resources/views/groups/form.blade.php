<div class="form-group">
		{!! Form::label('name', trans('group.name')) !!}
		{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
		</div>

<div class="form-group">
		{!! Form::label('body', trans('group.description')) !!}
		{!! Form::textarea('body', null, ['class' => 'form-control', 'required']) !!}
</div>
