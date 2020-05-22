<div class="alerts" style="position: sticky; top: 2rem; width: 100%; z-index: 2000" up-hungry>


  @if (isset(Auth::user()->verified) && (Auth::user()->verified == 0))
    <div class="alert alert-primary" role="alert">
      <h4 class="alert-heading">
        <i class="fa fa-envelope-open-text"></i> {{__('Please verify your email address')}}
      </h4>
      {{trans('messages.email_not_verified')}}
      <br/>
      <a class="alert-link" href="{{route('users.sendverification', Auth::user())}}">
        {{trans('messages.email_not_verified_send_again_verification')}}
      </a>
    </div>
  @endif

  @if (Auth::user() && Auth::user()->invites->count() > 0)
    <div class="alert alert-primary" role="alert">
      <h4 class="alert-heading"><i class="fa fa-hand-point-right"></i>
        {{__('You have pending group invites')}}
      </h4>

      <a class="alert-link" href="{{route('invites.index')}}" up-modal=".dialog">
        {{__('Click here to accept or deny the invitation(s)')}}
      </a>
    </div>
  @endif



  @if ( Session::has('messages') )
    @foreach (session('messages') as $message)
      <div class="alert alert-primary" role="alert">
        {!!$message!!}
        <?php session()->pull('messages'); ?>
      </div>
    @endforeach
  @endif



  @if ($errors->any())
    @foreach ($errors->all() as $error)
      <div class="alert alert-danger" role="alert">
        {{ $error }}
      </div>
    @endforeach
  @endif




</div>
