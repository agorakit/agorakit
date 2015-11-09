


<div class="form-group">
	{!! Form::label('name', 'Title') !!}
	{!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
	{!! Form::label('body', 'Description') !!}
	{!! Form::textarea('body', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
	{!! Form::label('location', 'Localisation / adresse') !!}
	{!! Form::textarea('location', null, ['class' => 'form-control']) !!}
</div>

<div class="row">

	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label('start', 'Début') !!}<br/>
			{!! Form::text('start', \Carbon\Carbon::now()->format('Y-m-d H:i') , ['class' => 'form-control', 'id' => 'start']) !!}

		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!! Form::label('stop', 'Fin') !!}<br/>
			{!! Form::text('stop', null, ['class' => 'form-control' , 'id' => 'stop']) !!}
		</div>
	</div>
</div>
