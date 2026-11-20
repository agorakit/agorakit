@extends('app')

@section('content')
    {!! Form::open(['action' => ['GroupMembershipController@update', $group, $membership]]) !!}

    <h1>{{ t('Notifications') }}</h1>
    <div class="help">
        <strong>{{ t('How does it work?') }}</strong>
        <p>
            {{ t('Here you can choose if you want to receive news from this group by email.') }}
        </p>
    </div>

    <div class="form-group">
        {!! Form::label('notifications', t('How often do you want to receive notifications by email?')) !!}
        {!! Form::select(
            'notifications',
            [
                'instantly' => t('As soon as possible'),
                'hourly' => t('Every hour'),
                'daily' => t('Every day'),
                'weekly' => t('Every week'),
                'biweekly' => t('Every two weeks'),
                'monthly' => t('Every month'),
                'never' => t('Never'),
            ],
            $interval,
            ['class' => 'form-control'],
        ) !!}
    </div>

    <div class="mt-5 d-flex justify-content-between align-items-center">

        <div class="form-group">
            {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
        </div>

        <div>
            <a href="{{ action('GroupMembershipController@destroyConfirm', $group) }}">
                {{ t('If you want to leave this group') }}
            </a>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
