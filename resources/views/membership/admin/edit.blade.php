@extends('app')

@section('content')
    <h1>{{ trans('messages.edit') }} {{ $membership->user->name }} in "{{ $group->name }}"</h1>

    {!! Form::open(['action' => ['GroupMembershipAdminController@update', $group, $membership]]) !!}

    <strong>{{ trans('messages.notifications_interval') }}</strong>

    <div class="form-group">
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

    @can('manage-membership', $group)
        <strong>{{ trans('membership.status') }}</strong>
        <div class="form-group">

            {!! Form::select(
                'membership_level',
                [
                    \Agorakit\Membership::ADMIN => trans('membership.admin'),
                    \Agorakit\Membership::MEMBER => trans('membership.member'),
                    \Agorakit\Membership::INVITED => trans('membership.invited'),
                    \Agorakit\Membership::CANDIDATE => trans('membership.candidate'),
                    \Agorakit\Membership::REMOVED => trans('membership.removed'),
                    \Agorakit\Membership::DECLINED => trans('membership.declined'),
                    \Agorakit\Membership::BLACKLISTED => trans('membership.blacklisted'),
                ],
                $membership->membership,
                ['class' => 'form-control'],
            ) !!}
        </div>
    @endcan

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
