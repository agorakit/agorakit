<div class="form-group">
	{!! Form::label('notifications', 'Email notifications') !!}
	{!! Form::select('notifications', ['hourly' => 'Every hour', 'daily' => 'Every day', 'weekly' => 'Every week', 'biweekly' => 'Every two weeks', 'monthly' => 'Every month', 'never' => 'Never notify me'], 'weekly', ['class' => 'form-control']) !!}
</div>
