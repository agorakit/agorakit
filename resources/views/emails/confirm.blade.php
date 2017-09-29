@component('mail::message')

<h1>{{trans('messages.thank_you_for_joining')}} "{{config('app.name')}}"</h1>

<p>
{{trans('messages.we_need_to_confirm')}}
</p>

@component('mail::button', ['url' => action('Auth\RegisterController@confirmEmail', [$user->token])])
{{trans('messages.click_to_confirm')}}
@endcomponent

<small>{{trans('messages.if_you_didnt_ask')}} "{{config('app.name')}}", {{trans('messages.do_nothing')}}.</small>


@endcomponent
