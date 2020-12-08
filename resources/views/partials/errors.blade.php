
<div class="js-spinner sticky  hidden bg-green-700 top-0 z-50 w-full">
        <div class="inline-block  text-green-200 m-4">
            <i class="far fa-save mr-2"></i>
            {{__('Loading')}}
        </div>
</div>

 <div class="js-network-error sticky hidden bg-red-700 top-0 z-50 w-full">
        <div class="inline-block  text-red-200 m-4">
            <i class="fa fa-plug mr-2"></i>
            {{__('Network error')}}
        </div>
</div>






  @if (isset(Auth::user()->verified) && (Auth::user()->verified == 0))
   <div class="bg-blue-700 sticky top-0 z-50 w-full text-blue-100 p-4" up-hungry>
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
   <div class="bg-pink-700 sticky top-0 z-50 w-full text-pink-100 p-4" up-hungry>
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
        {!!$message!!}
        <?php session()->pull('messages'); ?>
    @endforeach
  @endif

  @if ( Session::has('message') )
      {!!Session::get('message')!!}
  @endif




  @if ($errors->any())
    @foreach ($errors->all() as $error)
        {{ $error }}
    @endforeach
  @endif



</div>
</div>
