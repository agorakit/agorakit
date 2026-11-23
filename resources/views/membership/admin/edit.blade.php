@extends('app')

@section('content')
    <h1>{{ trans('Edit') }} {{ $membership->user->name }} in "{{ $group->name }}"</h1>

    {!! Form::open(['action' => ['GroupMembershipAdminController@update', $group, $membership]]) !!}

    <strong>{{ trans('Notifications') }}</strong>

    <div class="form-group">
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

    @can('manage-membership', $group)
        <strong>{{ trans('Status') }}</strong>
        <div class="form-group">

            {!! Form::select(
                'membership_level',
                [
                    \App\Membership::ADMIN => trans('administrator'),
                    \App\Membership::MEMBER => trans('member'),
                    \App\Membership::INVITED => trans('invited'),
                    \App\Membership::CANDIDATE => trans('candidate'),
                    \App\Membership::REMOVED => trans('removed'),
                    \App\Membership::DECLINED => trans('declined'),
                    \App\Membership::BLACKLISTED => trans('blacklisted'),
                ],
                $membership->membership,
                ['class' => 'form-control'],
            ) !!}
        </div>
    @endcan

    <div class="form-group">
        {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
