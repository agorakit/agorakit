@component('mail::message')

<h1>{{trans('Thank you for joining')}} "{{setting('name')}}"</h1>

<p>
{{trans('We need to confirm your account')}}
</p>

@component('mail::button', ['url' => action('Auth\RegisterController@confirmEmail', [$user->getToken()])])
{{trans('Click to confirm')}}
@endcomponent

<small>{{trans('If you didn\'t ask to join')}} "{{setting('name')}}", {{trans('Do nothing')}}.</small>


@endcomponent
