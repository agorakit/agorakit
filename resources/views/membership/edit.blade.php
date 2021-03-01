@extends('app')

@section('content')

@include('groups.tabs')

<div class="tab_content">

   


    {!! Form::open(array('action' => ['GroupMembershipController@update', $group, $membership])) !!}


    <h1>{{trans('messages.notifications_interval')}}</h1>
    <div class="help">
        <strong>{{trans('membership.settings_how_does_it_works')}}</strong>
        <p>
            {{trans('membership.settings_intro')}}
        </p>
    </div>

    <div class="form-group">
        {!! Form::label('notifications', trans('membership.when_to_receive_notifications') ) !!}
        {!! Form::select('notifications', ['instantly' => trans('membership.instantly'), 'hourly' =>
        trans('membership.everyhour'), 'daily' => trans('membership.everyday'), 'weekly' =>
        trans('membership.everyweek'), 'biweekly' => trans('membership.everytwoweek'), 'monthly' =>
        trans('membership.everymonth'), 'never' => trans('membership.never')], $interval, ['class' => 'form-control'])
        !!}
    </div>



    <div class="mt-5 flex justify-between items-center">

        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
        </div>



        <div>{{trans('membership.if_you_want_to_leave_this_group')}}, <a up-follow
                href="{{action('GroupMembershipController@destroyConfirm', $group)}}">{{trans('membership.click_here')}}</a>
        </div>
    </div>

    {!! Form::close() !!}




</div>

@endsection