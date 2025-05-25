@extends('app')

@section('content')
    {!! Form::open(['action' => ['GroupMembershipController@update', $group, $membership]]) !!}

    <h1>{{ trans('messages.notifications_interval') }}</h1>
    <div class="help">
        <strong>{{ trans('membership.settings_how_does_it_works') }}</strong>
        <p>
            {{ trans('membership.settings_intro') }}
        </p>
    </div>

    <div class="form-group">
        {!! Form::label('notifications', trans('membership.when_to_receive_notifications')) !!}
        {!! Form::select(
            'notifications',
            [
                'instantly' => trans('membership.instantly'),
                'hourly' => trans('membership.everyhour'),
                'daily' => trans('membership.everyday'),
                'weekly' => trans('membership.everyweek'),
                'biweekly' => trans('membership.everytwoweek'),
                'monthly' => trans('membership.everymonth'),
                'never' => trans('membership.never'),
            ],
            $interval,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="mt-5 d-flex justify-content-between align-items-center">

        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
        </div>

        <div>
            <a href="{{ action('GroupMembershipController@destroyConfirm', $group) }}">
                {{ trans('membership.if_you_want_to_leave_this_group') }}
            </a>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
