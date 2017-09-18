@component('mail::message')

<strong>{{trans('messages.hello')}}</strong>

<p>
<a href="{{action('UserController@show', $invite->user)}}">{{$invite->user->name}}</a> {{trans('messages.thinks_that_you_might_want_to_join')}} "<a href="{{action('GroupController@show',  [$invite->group] )}}">{{$invite->group->name}}</a>"
{{trans('messages.inside')}} <a href="{{action('DashboardController@index')}}">{{env('APP_NAME')}}</a>
</p>

<p>
{{trans('messages.this_will_allow_you_to_be_informed')}}
</p>

<p>{{trans('messages.here_is_the_description_of_the_group')}} :
@component('mail::panel')
<p>{!! filter($invite->group->body) !!}</p>
@endcomponent

@component('mail::button', ['url' => action('InviteController@inviteConfirm', [$invite->group, $invite->token])])
{{trans('messages.accept_invitation')}}
@endcomponent

<small>{{trans('messages.if_you_donwt_want_to_join_do_nothing')}}</small>


@endcomponent
