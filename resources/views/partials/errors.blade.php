<div class="alerts" style="position: sticky; top: 70px; width: 100%; z-index: 800" up-hungry>

    @if (isset(Auth::user()->verified) && Auth::user()->verified == 0)
        <div class="alert alert-warning">
            <h4 class="alert-title">
                <i class="fa fa-envelope-open-text"></i> {{ __('Please verify your email address') }}
            </h4>
            <div class="text-secondary">
                {{ trans('messages.email_not_verified') }}
                <br />
                <a class="alert-link" href="{{ route('users.sendverification', Auth::user()) }}">
                    {{ trans('messages.email_not_verified_send_again_verification') }}
                </a>
            </div>
        </div>
    @endif

    @if (Auth::user() && Auth::user()->invites->count() > 0)
        <div class="alert alert-info">
            <h4 class="alert-heading"><i class="fa fa-hand-point-right"></i>
                {{ __('You have pending group invites') }}
            </h4>

            <a class="alert-link" href="{{ route('invites.index') }}" up-modal=".dialog">
                {{ __('Click here to accept or deny the invitation(s)') }}
            </a>
        </div>
    @endif

    @if (Session::has('messages'))
        <div class="alert alert-info">
            @foreach (session('messages') as $message)
                <div>{!! $message !!}</div>
                <?php session()->pull('messages'); ?>
            @endforeach
        </div>
    @endif

    @if (Session::has('warnings'))
        <div class="alert alert-warning">
            @foreach (session('warnings') as $message)
                <div>{!! $message !!}</div>
                <?php session()->pull('warnings'); ?>
            @endforeach
        </div>
    @endif

    @if (Session::has('message'))
        <div class="alert alert-info">
            {!! Session::get('message') !!}
        </div>
    @endif

    @if (Session::has('errors'))
        <div class="alert alert-danger">
            @foreach (session('errors') as $message)
                <div>{!! $message !!}</div>
                <?php session()->pull('errors'); ?>
            @endforeach
        </div>
    @endif

</div>
