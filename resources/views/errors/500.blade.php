@extends('errors::layout')

@section('title', 'Application error')

@section('message')

<div class="container main-container">

<h1>A fatal error occured</h1>

<br/>
<small>
{{$exception->getMessage()}}
<p>Contact the server admin to get help and explain what you were trying to do. Provide this error message to help fix the bug.</p>
</small>

</div>


@endsection
