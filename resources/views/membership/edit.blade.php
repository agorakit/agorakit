@extends('app')

@section('content')
    {!! Form::open(['action' => ['GroupMembershipController@update', $group, $membership]]) !!}

    <h1>{{ trans('Notifications') }}</h1>
    <div class="help">
        <strong>{{ trans('How does it work?') }}</strong>
        <p>
            {{ trans('Here you can choose if you want to receive news from this group by email.') }}
        </p>
    </div>

    <div class="form-group">
        {!! Form::label('notifications', trans('How often do you want to receive notifications by email?')) !!}
        {!! Form::select(
            'notifications',
            [
                'instantly' => trans('As soon as possible'),
                'hourly' => trans('Every hour'),
                'daily' => trans('Every day'),
                'weekly' => trans('Every week'),
                'biweekly' => trans('Every two weeks'),
                'monthly' => trans('Every month'),
                'never' => trans('Never'),
            ],
            $interval,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="mt-5 d-flex justify-content-between align-items-center">

        <div class="form-group">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
        </div>

        <div>
            <a href="{{ action('GroupMembershipController@destroyConfirm', $group) }}">
                {{ trans('If you want to leave this group') }}
            </a>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
