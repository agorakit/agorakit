@extends('dialog')

@auth
	@section('content')

		<h1>This action is unauthorized</h1>

	@endsection
@endauth



@guest
	@section('content')

		@include('auth.login-form')
	@endsection
@endguest
