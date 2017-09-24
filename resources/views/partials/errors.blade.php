@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong><i class="fa fa-exclamation-triangle"></i>{{ trans('messages.howdy') }}</strong> {{ trans('messages.something_wrong') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ( Session::has('message') )
    <div class="alert alert-info alert-dismissible fade in" id="message">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-info-circle"></i>
        {!! Session::get('message') !!}
    </div>
@endif


@if ( Session::has('error') )
    <div class="alert alert-danger alert-dismissible fade in" id="error">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-exclamation-triangle"></i>
        {{ Session::get('error') }}
    </div>
@endif


@include('flash::message')
