@component('mail::message')

<strong>{{trans('Hello')}}</strong>


<a href="{{route('users.show', $group_user)}}">{{$group_user->name}}</a>
{{trans('thinks that you might want to join')}} "
<a href="{{route('groups.show',  [$membership->group] )}}">{{$membership->group->name}}</a>"
{{trans('inside')}} <a  href="{{route('index')}}">{{setting('name')}}</a>



{{trans('this will allow you to be informed and to participate')}}


{{trans('Here is the description of the group')}} :
@component('mail::panel')
{!! filter($membership->group->body) !!}</p>
@endcomponent

@component('mail::button', ['url' => $accept_url, 'color' => 'success'])
{{trans('Accept the invitation')}}
@endcomponent

<div style="text-align: center">
<a href="{{$deny_url}}">
{{trans('Refuse the invitation')}}
</a>
</div>


@endcomponent
