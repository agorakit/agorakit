@component('mail::message')

<strong>{{trans('messages.hello')}} {{$to_user->name}},</strong>

<p>
<a up-follow href="{{route('users.show', $from_user)}}">{{$from_user->name}}</a> {{trans('messages.sent_you_a_message')}} :
</p>

<p>
{!! nl2br(e($body)) !!}
</p>

@component('mail::button', ['url' => route('users.contactform', $from_user)])
{{trans('messages.reply')}}
@endcomponent



@endcomponent
