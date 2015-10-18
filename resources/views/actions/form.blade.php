


	<div class="form-group">
			{!! Form::label('name', 'Title') !!}
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			</div>

	<div class="form-group">
			{!! Form::label('body', 'Description') !!}
			{!! Form::textarea('body', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
		{!! Form::label('start', 'Start') !!}
		{!! Form::input('date', 'start', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}

		</div>


		<div class="form-group">
				{!! Form::label('stop', 'Stop') !!}
				{!! Form::input('date', 'stop', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
				</div>
