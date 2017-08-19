@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="help" role="alert">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
			{{trans('messages.if_you_already_have_account')}}, <a href="{{url('login')}}">{{trans('messages.you_can_login_here')}}</a>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('messages.register') }}</div>
				<div class="panel-body">



					@if (isset($invite_and_register))
					<form class="form-horizontal" role="form" method="POST" action="{{ action('InviteController@inviteRegister') }}">
					@else
					<form class="form-horizontal" role="form" method="POST" action="{{ url('register') }}">
					@endif


						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.name') }}</label>
							<div class="col-md-6">
								<input type="text" class="form-control" required="required"  name="name" value="{{ old('name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.email') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" required="required" name="email" value="@if (isset($email)) {{$email}}@else{{ old('email')}}@endif">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.password') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" required="required" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.confirm_password') }}</label>
							<div class="col-md-6">
								<input type="password" class="form-control" required="required" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ trans('messages.register') }}
								</button>
							</div>


                            <hr>
  <div class="form-group">
      <div class="col-md-6 col-md-offset-4">
          <a href="{{ url('/auth/github') }}" class="btn btn-github"><i class="fa fa-github"></i> Github</a>
          <a href="{{ url('/auth/twitter') }}" class="btn btn-twitter"><i class="fa fa-twitter"></i> Twitter</a>
          <a href="{{ url('/auth/facebook') }}" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
      </div>
  </div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
