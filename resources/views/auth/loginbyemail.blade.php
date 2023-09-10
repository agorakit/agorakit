

@extends('dialog')

@section('content')

	<h1>Send me a login link by email</h1>

	<form method="POST" action="{{ route('sendloginbyemail') }}">

		@csrf
		@honeypot

		<div class="form-group">
			<label>{{ __('Just enter your email :') }}</label>
			<div>
				<input type="email" name="email" required class="form-control" @if (isset($email)) value="{{$email}}"@endif>
			</div>
		</div>


		<div class="d-flex justify-content-center mt-2">
			<button type="submit" class="btn btn-primary">@lang('Send me the link')</button>
		</div>



	</form>

@endsection
