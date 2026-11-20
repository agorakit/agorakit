@component('mail::message')

<strong>{{t('Hello')}} {{$to_user->name}},</strong>

<p>
<a  href="{{route('users.show', $from_user)}}">{{$from_user->name}}</a> {{t('sent you a message')}} :
</p>

<p>
{!! nl2br(e($body)) !!}
</p>

@component('mail::button', ['url' => route('users.contactform', $from_user)])
{{t('Reply')}}
@endcomponent
<small>{{t('Don\'t reply to this email, use the reply button(s) instead')}}</small>


@endcomponent
