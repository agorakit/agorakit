<div class="form-group">
	{!! Form::label('notifications', trans('How often do you want to receive notifications by email?') ) !!}
	{!! Form::select('notifications', ['instantly' => trans('As soon as possible'), 'hourly' => trans('Every hour'), 'daily' => trans('Every day'), 'weekly' => trans('Every week'), 'biweekly' => trans('Every two weeks'), 'monthly' => trans('Every month'), 'never' => trans('Never')], $interval, ['class' => 'form-control']) !!}
</div>
