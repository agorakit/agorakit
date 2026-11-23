 <div class="bottom-messages">

     <div class="js-loader">
         <div class="d-flex gap-2 align-items-center">
             <i class="far fa-save mr-2"></i>
             {{ trans('Loading') }}
         </div>
     </div>

     <div class="js-network-error">
         <div class="d-flex gap-2 align-items-center">
             <i class="fa fa-plug mr-2"></i>
             {{ trans('Network error') }}
         </div>
     </div>
 </div>


 <div class="messages" up-hungry>
     @if (isset(Auth::user()->verified) && Auth::user()->verified == 0)
         <div class="alert text-bg-warning">
             <strong>
                 <i class="fa fa-envelope-open-text"></i> {{ trans('Please verify your email address') }}
             </strong>
             <br />
             {{ trans('Email not verified') }}
             <br />
             <a class="text-white" href="{{ route('users.sendverification', Auth::user()) }}">
                 {{ trans('Click here to send your verification email again') }}
             </a>

         </div>
     @endif

     @if (Auth::user() && Auth::user()->invites->count() > 0)
         <div class="alert text-bg-info">
             <strong>
                 <i class="fa fa-hand-point-right"></i>
                 {{ trans('You have pending group invites') }}
             </strong>
             <br />
             <a href="{{ route('invites.index') }}" up-modal=".dialog">
                 {{ trans('Click here to accept or deny the invitation(s)') }}
             </a>
         </div>
     @endif

     @if (Session::has('messages'))
         <div class="alert text-bg-info">
             @foreach (session('messages') as $message)
                 <div>{{ $message }}</div>
                 <?php session()->pull('messages'); ?>
             @endforeach
         </div>
     @endif

     @if (Session::has('warnings'))
         <div class="alert text-bg-warning">
             @foreach (session('warnings') as $message)
                 <div>{{ $message }}</div>
                 <?php session()->pull('warnings'); ?>
             @endforeach
         </div>
     @endif

     @if (Session::has('agorakit_errors'))
         <div class="alert alert-danger">
             @foreach (session('agorakit_errors') as $message)
                 <div>{{ $message }}</div>
                 <?php session()->pull('agorakit_errors'); ?>
             @endforeach
         </div>
     @endif

     @if (Session::has('message'))
         <div class="alert text-bg-info">
             {!! Session::get('message') !!}
         </div>
     @endif

     @if ($errors->any())
         <div class="alert text-bg-danger">
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     @endif


 </div>
