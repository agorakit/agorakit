@auth
	@extends('errors::layout')

	@section('title', 'Unauthorized')

	@section('message')

		This action is unauthorized

	@endsection
@endauth



@guest
	@extends('dialog')

	@section('content')

		@include('auth.login-form')

	@endsection
@endguest
