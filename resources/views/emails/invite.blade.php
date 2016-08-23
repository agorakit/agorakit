@extends('emails.template')

@section('content')




<strong>{{trans('messages.hello')}}</strong>

<p>
  {{$invitating_user->name}} {{trans('messages.thinks_that_you_might_want_to_join')}} "<a href="{{action('GroupController@show',  [$group->id] )}}">{{$group->name}}</a>"
   {{trans('messages.inside')}} <a href="{{action('DashboardController@index')}}">{{env('APP_NAME')}}</a>
</p>
<p>
{{trans('messages.this_will_allow_you_to_be_informed')}}

</p>

<p>{{trans('messages.here_is_the_description_of_the_group')}} :
<p>{!! filter($group->body) !!}</p>


@include('emails.button', ['url' => action('InviteController@inviteConfirm', [$group->id, $invite->token]), 'label' => trans('messages.accept_invitation')])
<p>({{trans('messages.this_action_can_be_reverted')}})</p>

<p style="font-size: 0.8em">{{trans('messages.if_you_donwt_want_to_join_do_nothing')}}</p>


@endsection
