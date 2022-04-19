@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.edit')}} {{$membership->user->name}} in "{{$group->name}}"</h1>




        {!! Form::open(array('action' => ['GroupMembershipAdminController@update', $group, $membership])) !!}


        <strong>{{trans('messages.notifications_interval')}}</strong>

        <div class="form-group">
            {!! Form::select('notifications', ['instantly' => trans('membership.instantly'), 'hourly' => trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' => trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' => trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control']) !!}
        </div>


        @can('manage-membership', $group)
            <strong>{{trans('membership.status')}}</strong>
            <div class="form-group">

                {!! Form::select('membership_level',
                    [
                        \App\Models\Membership::ADMIN => trans('membership.admin'),
                        \App\Models\Membership::MEMBER => trans('membership.member'),
                        \App\Models\Membership::INVITED => trans('membership.invited'),
                        \App\Models\Membership::CANDIDATE => trans('membership.candidate'),
                        \App\Models\Membership::REMOVED => trans('membership.removed'),
                        \App\Models\Membership::DECLINED => trans('membership.declined'),
                        \App\Models\Membership::BLACKLISTED => trans('membership.blacklisted'),
                    ],
                    $membership->membership, ['class' => 'form-control']) !!}
                </div>

            @endcan

            <div class="form-group">
                {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
            </div>


            {!! Form::close() !!}


        </div>

    @endsection
