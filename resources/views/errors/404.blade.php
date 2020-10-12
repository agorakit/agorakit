@extends('errors::layout')

@section('title', 'Page Not Found')

@section('message')

<div class="container main-container">

<h1>Sorry, the page you are looking for could not be found.</h1>

<br/>
<small>
{{$exception->getMessage()}}
</small>

</div>

@endsection
