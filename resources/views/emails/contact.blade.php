@component('mail::message')

<strong>{{trans('Hello')}} {{$to_user->name}},</strong>

<p>
<a  href="{{route('users.show', $from_user)}}">{{$from_user->name}}</a> {{trans('sent you a message')}} :
</p>

<p>
{!! nl2br(e($body)) !!}
</p>

@component('mail::button', ['url' => route('users.contactform', $from_user)])
{{trans('Reply')}}
@endcomponent
<small>{{trans('Don\'t reply to this email, use the reply button(s) instead')}}</small>


@endcomponent
