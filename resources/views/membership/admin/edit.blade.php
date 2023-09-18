@extends('group')

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
                    \App\Membership::ADMIN => trans('membership.admin'),
                    \App\Membership::MEMBER => trans('membership.member'),
                    \App\Membership::INVITED => trans('membership.invited'),
                    \App\Membership::CANDIDATE => trans('membership.candidate'),
                    \App\Membership::REMOVED => trans('membership.removed'),
                    \App\Membership::DECLINED => trans('membership.declined'),
                    \App\Membership::BLACKLISTED => trans('membership.blacklisted'),
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
