@extends('dialog')

@section('content')

	<h1>{{ trans('messages.password_reset') }}</h1>


	<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="token" value="{{ $token }}">

		<div class="form-group">
			<label>{{ trans('messages.mail') }}</label>
			<input type="email" class="form-control" name="email" value="{{ old('email') }}" required>

		</div>

		<div class="form-group">
			<label>{{ trans('messages.password') }}</label>
			<input type="password" class="form-control" name="password" required>

		</div>

		<div class="form-group">
			<label>{{ trans('messages.confirm_password') }}</label>
			<input type="password" class="form-control" name="password_confirmation" required>
		</div>

		<div class="form-group">
			<div>
				<button type="submit" class="btn btn-primary">
					{{ trans('messages.change_my_password') }}
				</button>
			</div>
		</div>
	</form>

@endsection
