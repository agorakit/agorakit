


	<div class="form-group">
			{!! Form::label('name', 'Name:') !!}
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			</div>

	<div class="form-group">
			{!! Form::label('body', 'Body:') !!}
			{!! Form::textarea('body', null, ['class' => 'form-control']) !!}
</div>

	<div class="form-group">
			{!! Form::label('parent_id', 'In reply to:') !!}
			{!! Form::text('parent_id', null, ['class' => 'form-control']) !!}
		</div>
