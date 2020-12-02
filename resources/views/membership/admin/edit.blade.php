@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.edit')}} {{$membership->user->name}} in "{{$group->name}}"</h1>




        {!! Form::open(array('action' => ['GroupMembershipAdminController@update', $group, $membership])) !!}


        <h2>{{trans('messages.notifications_interval')}}</h2>

        <div class="form-group">
            {!! Form::select('notifications', ['instantly' => trans('membership.instantly'), 'hourly' => trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' => trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' => trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control']) !!}
        </div>


        @can('manage-membership', $group)
            <h2>{{trans('membership.status')}}</h2>
            <div class="form-group">

                {!! Form::select('membership_level',
                    [
                        \App\Membership::ADMIN => trans('membership.admin'),
                        \App\Membership::MEMBER => trans('membership.member'),
                        \App\Membership::REMOVED => trans('membership.removed'),
                        \App\Membership::BLACKLISTED => trans('membership.blacklisted'),
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
