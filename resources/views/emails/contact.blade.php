@component('mail::message')

<strong>{{trans('messages.hello')}} {{$to_user->name}},</strong>

<p>
  <a href="{{action('UserController@show', $from_user)}}">{{$from_user->name}}</a> {{trans('messages.sent_you_a_message')}} :
</p>

<p>
{!! nl2br(e($body)) !!}
</p>

@component('mail::button', ['url' => action('UserController@contact', $from_user)])
{{trans('messages.reply')}}
@endcomponent
<small>{{trans('messages.you_can_also_reply')}}</small>


@endcomponent
