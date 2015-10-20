


	<div class="form-group">
			{!! Form::label('name', 'Title') !!}
			{!! Form::text('name', null, ['class' => 'form-control']) !!}
			</div>

	<div class="form-group">
			{!! Form::label('body', 'Description') !!}
			{!! Form::textarea('body', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
		{!! Form::label('start', 'Start') !!}<br/>
		{!! Form::text('start', \Carbon\Carbon::now()->format('Y-m-d H:i') , ['class' => 'form-control', 'id' => 'start']) !!}

		</div>


		<div class="form-group">
				{!! Form::label('stop', 'Stop') !!}<br/>
				{!! Form::text('stop', null, ['class' => 'form-control' , 'id' => 'stop']) !!}
				</div>
