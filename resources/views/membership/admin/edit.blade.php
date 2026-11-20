@extends('app')

@section('content')
    <h1>{{ t('Edit') }} {{ $membership->user->name }} in "{{ $group->name }}"</h1>

    {!! Form::open(['action' => ['GroupMembershipAdminController@update', $group, $membership]]) !!}

    <strong>{{ t('Notifications') }}</strong>

    <div class="form-group">
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

    @can('manage-membership', $group)
        <strong>{{ t('Status') }}</strong>
        <div class="form-group">

            {!! Form::select(
                'membership_level',
                [
                    \App\Membership::ADMIN => t('administrator'),
                    \App\Membership::MEMBER => t('member'),
                    \App\Membership::INVITED => t('invited'),
                    \App\Membership::CANDIDATE => t('candidate'),
                    \App\Membership::REMOVED => t('removed'),
                    \App\Membership::DECLINED => t('declined'),
                    \App\Membership::BLACKLISTED => t('blacklisted'),
                ],
                $membership->membership,
                ['class' => 'form-control'],
            ) !!}
        </div>
    @endcan

    <div class="form-group">
        {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
