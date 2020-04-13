@component('mail::message')

<strong>{{trans('messages.hello')}}</strong>

<p>
<a href="{{route('users.show', $group_user)}}">{{$group_user->name}}</a>
{{trans('messages.thinks_that_you_might_want_to_join')}} "
<a href="{{route('groups.show',  [$membership->group] )}}">{{$membership->group->name}}</a>"
{{trans('messages.inside')}} <a href="{{route('index')}}">{{setting('name')}}</a>
</p>

<p>
{{trans('messages.this_will_allow_you_to_be_informed')}}
</p>

<p>{{trans('messages.here_is_the_description_of_the_group')}} :
@component('mail::panel')
<p>{!! filter($membership->group->body) !!}</p>
@endcomponent

@component('mail::button', ['url' => route('login')])
{{__('Login or create an account to accept the invitation')}}
@endcomponent

<small>{{trans('messages.if_you_donwt_want_to_join_do_nothing')}}</small>


@endcomponent
