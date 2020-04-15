<h1>{{trans('messages.login')}}</h1>

<p class="help">
  <i class="fa fa-info-circle" aria-hidden="true"></i>
  {{trans('messages.if_you_dont_have_account')}}, <a href="{{url('register')}}">{{trans('messages.you_can_create_one_here')}}</a>
</p>

<form method="POST" action="{{ url('/login') }}">

  @csrf
  @honeypot

  <div class="form-group">
    <label>{{ __('Username or Email') }}</label>
    <div>
      <input type="text" class="form-control" name="login" required="required" value="{{ old('username') ?: old('email') }}">
    </div>

    @error('login')
      <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>

  <div class="form-group">
    <label>{{ trans('messages.password') }}</label>
    <div>
      <input type="password" required="required" class="form-control" name="password">
    </div>
  </div>

  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="remember" checked value="1" id="remember_me">
    <label class="form-check-label" for="remember_me">
      {{ trans('messages.remember_me') }}
    </label>
  </div>

  <div class="d-flex justify-content-end mt-5">
    <a class="btn btn-link mr-4" href="{{ url('/password/reset') }}">{{ trans('messages.forgotten_password') }}</a>

    <button type="submit" class="btn btn-primary">{{ trans('messages.login') }}</button>
  </div>

  <p class="help mt-5">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    @lang('If you prefer, you can receive a login link by email'),
    <a up-target="body" href="{{ url('/loginbyemail') }}">@lang('click here to receive your link')</a>.
  </p>


</form>
