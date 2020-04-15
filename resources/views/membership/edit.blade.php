@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.preferences_for')}} {{$membership->user->name}} in "{{$group->name}}"</h1>




        {!! Form::open(array('action' => ['GroupMembershipController@update', $group, $membership])) !!}


        <h2>{{trans('messages.notifications_interval')}}</h2>
        <div class="help">
            <strong>{{trans('membership.settings_how_does_it_works')}}</strong>
            <p>
                {{trans('membership.settings_intro')}}
            </p>
        </div>

        <div class="form-group">
            {!! Form::label('notifications', trans('membership.when_to_receive_notifications') ) !!}
            {!! Form::select('notifications', ['instantly' => trans('membership.instantly'), 'hourly' => trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' => trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' => trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control']) !!}
        </div>


        @can('manage-membership', $group)
            <h2>Membership status</h2>
            <div class="form-group">

                {!! Form::select('membership_level',
                    [
                        \App\Membership::BLACKLISTED => trans('membership.blacklisted'),
                        \App\Membership::REMOVED => trans('membership.removed'),
                        \App\Membership::DECLINED => trans('membership.declined'),
                        \App\Membership::UNREGISTERED => trans('membership.unregistered'),
                        \App\Membership::INVITED => trans('membership.invited'),
                        \App\Membership::CANDIDATE => trans('membership.candidate'),
                        \App\Membership::MEMBER => trans('membership.member'),
                        \App\Membership::ADMIN => trans('membership.admin'),
                    ],
                    $membership->membership, ['class' => 'form-control']) !!}
                </div>

            @endcan

            <div class="form-group">
                {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
            </div>


            {!! Form::close() !!}




            <p>{{trans('membership.if_you_want_to_leave_this_group')}}, <a href="{{action('GroupMembershipController@destroyConfirm', $group)}}">{{trans('membership.click_here')}}</a></p>


        </div>

    @endsection
