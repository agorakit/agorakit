<div class="form-group">
	{!! Form::label('name', trans('messages.title')) !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
	<input type="color" name="color" value="{{$tag->color}}">

</div>
