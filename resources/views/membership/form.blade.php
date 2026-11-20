<div class="form-group">
	{!! Form::label('notifications', t('How often do you want to receive notifications by email?') ) !!}
	{!! Form::select('notifications', ['instantly' => t('As soon as possible'), 'hourly' => t('Every hour'), 'daily' => t('Every day'), 'weekly' => t('Every week'), 'biweekly' => t('Every two weeks'), 'monthly' => t('Every month'), 'never' => t('Never')], $interval, ['class' => 'form-control']) !!}
</div>
