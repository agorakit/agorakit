<div class="form-group">
	{!! Form::label('notifications', trans('membership.when_to_receive_notifications') ) !!}
	{!! Form::select('notifications', ['instantly' => trans('membership.instantly'), 'hourly' => trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' => trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' => trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control']) !!}
</div>
