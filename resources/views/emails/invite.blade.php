@component('mail::message')

<strong>{{t('Hello')}}</strong>


<a href="{{route('users.show', $group_user)}}">{{$group_user->name}}</a>
{{t('thinks that you might want to join')}} "
<a href="{{route('groups.show',  [$membership->group] )}}">{{$membership->group->name}}</a>"
{{t('inside')}} <a  href="{{route('index')}}">{{setting('name')}}</a>



{{t('this will allow you to be informed and to participate')}}


{{t('Here is the description of the group')}} :
@component('mail::panel')
{!! filter($membership->group->body) !!}</p>
@endcomponent

@component('mail::button', ['url' => $accept_url, 'color' => 'success'])
{{t('Accept the invitation')}}
@endcomponent

<div style="text-align: center">
<a href="{{$deny_url}}">
{{t('Refuse the invitation')}}
</a>
</div>


@endcomponent
