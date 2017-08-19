@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="help" role="alert">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				{{trans('messages.if_you_dont_have_account')}}, <a href="{{url('register')}}">{{trans('messages.you_can_create_one_here')}}</a>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">{{trans('messages.login')}}</div>
				<div class="panel-body">




					<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.email') }}</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" required="required" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('messages.password') }}</label>
							<div class="col-md-6">
								<input type="password" required="required" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> {{ trans('messages.remember_me') }}
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">{{ trans('messages.login') }}</button>

								<a class="btn btn-link" href="{{ url('/password/reset') }}">{{ trans('messages.forgotten_password') }}</a>
							</div>
						</div>

                        <hr/>

                        @include('partials.socialite')

					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
