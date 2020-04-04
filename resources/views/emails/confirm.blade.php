@component('mail::message')

<h1>{{trans('messages.thank_you_for_joining')}} "{{setting('name')}}"</h1>

<p>
{{trans('messages.we_need_to_confirm')}}
</p>

@component('mail::button', ['url' => action('Auth\RegisterController@confirmEmail', [$user->getToken()])])
{{trans('messages.click_to_confirm')}}
@endcomponent

<small>{{trans('messages.if_you_didnt_ask')}} "{{setting('name')}}", {{trans('messages.do_nothing')}}.</small>


@endcomponent
