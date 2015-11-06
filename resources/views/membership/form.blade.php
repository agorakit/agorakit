<div class="form-group">
	{!! Form::label('notifications', 'Email notifications') !!}
	{!! Form::select('notifications', ['hourly' => trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' => trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' => trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control']) !!}
</div>
