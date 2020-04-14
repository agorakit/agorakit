@component('mail::message')

<strong>{{trans('messages.hello')}} {{$user->name}},</strong>

<p>
You asked for a login link
</p>


@component('mail::button', ['url' => $login_url])
Click here to login
@endcomponent



@endcomponent
