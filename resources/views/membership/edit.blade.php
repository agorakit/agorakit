@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.preferences_for')}} {{$membership->user->name}} in "{{$group->name}}"</h1>

        <div class="help">
            <h4>{{trans('membership.settings_how_does_it_works')}}</h4>
            <p>
                {{trans('membership.settings_intro')}}
            </p>
        </div>


        {!! Form::open(array('action' => ['MembershipController@update', $group, $membership])) !!}


        @include('membership.form')


        @can('edit-membership', $group)
            <h1>Membership status</h1>
            <div class="form-group">

                {!! Form::select('membership_level',
                    [
                        \App\Membership::BLACKLISTED => trans('membership.blacklisted'),
                        \App\Membership::REMOVED => trans('membership.removed'),
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
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
    </div>


    {!! Form::close() !!}




    <p>{{trans('membership.if_you_want_to_leave_this_group')}}, <a href="{{action('MembershipController@destroyConfirm', $group)}}">{{trans('membership.click_here')}}</a></p>


</div>

@endsection
