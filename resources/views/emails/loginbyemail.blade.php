@component('mail::message')

  <strong>{{trans('messages.hello')}} {{$user->name}},</strong>

  <p>
    @lang('You asked for a login link, click on the button below to login')
  </p>

  <p>
    @lang('This link will expire in 30 minutes')
  </p>


  @component('mail::button', ['url' => $login_url])
    @lang('Click here to login')
  @endcomponent


  @lang('If you didn\'t ask for this login link, simply do nothing.')

@endcomponent
