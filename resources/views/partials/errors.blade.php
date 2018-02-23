@if (isset(Auth::user()->verified) && (Auth::user()->verified == 0))
    <div class="alert alert-primary" role="alert">
        <i class="fa fa-info-circle"></i>
        {{trans('messages.email_not_verified')}}
        <br/>
        <a href="{{route('users.sendverification', Auth::user()->id)}}">{{trans('messages.email_not_verified_send_again_verification')}}</a>
    </div>
</div>
@endif



@if (count($errors) > 0)
    <div class="alert alert-primary" role="alert">
        <strong><i class="fa fa-exclamation-triangle"></i>{{ trans('messages.howdy') }}</strong> {{ trans('messages.something_wrong') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ( Session::has('message') )
    <div class="alert alert-primary" role="alert">
        <i class="fa fa-info-circle"></i>
        {!! Session::get('message') !!}
    </div>
@endif


@if ( Session::has('error') )
    <div class="alert alert-primary" role="alert">
        <i class="fa fa-exclamation-triangle"></i>
        {{ Session::get('error') }}
    </div>
@endif


@include('flash::message')
