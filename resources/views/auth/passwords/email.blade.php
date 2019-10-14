
@extends('dialog')

@section('content')
	<div class="container-fluid">

		<h1>{{trans('messages.change_my_password')}}</h1>


		@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
		@endif

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label> {{ trans('messages.email') }}</label>

					<input type="email" class="form-control" name="email" required="required"  value="{{ old('email') }}">

			</div>

			<div class="form-group">

					<button type="submit" class="btn btn-primary">
						{{ trans('messages.send_my_recover_email') }}
					</button>

			</div>
		</form>
	</div>

@endsection
