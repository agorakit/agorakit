@component('mail::message')

<strong>{{trans('messages.hello')}} {{$to_user->name}},</strong>

<p>
<a href="{{route('users.show', $from_user)}}">{{$from_user->name}}</a> {{trans('messages.sent_you_a_message')}} :
</p>

<p>
{!! nl2br(e($body)) !!}
</p>

@component('mail::button', ['url' => Autologin::to($to_user, route('users.contactform', $from_user))])
{{trans('messages.reply')}}
@endcomponent
<small>{{trans('messages.dont_reply_to_this_email')}}</small>


@endcomponent
