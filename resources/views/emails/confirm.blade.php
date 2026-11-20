@component('mail::message')

<h1>{{t('Thank you for joining')}} "{{setting('name')}}"</h1>

<p>
{{t('We need to confirm your account')}}
</p>

@component('mail::button', ['url' => action('Auth\RegisterController@confirmEmail', [$user->getToken()])])
{{t('Click to confirm')}}
@endcomponent

<small>{{t('If you didn\'t ask to join')}} "{{setting('name')}}", {{t('Do nothing')}}.</small>


@endcomponent
