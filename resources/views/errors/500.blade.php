@extends('errors::layout')

@section('title', 'Application error')

@section('message')

A fatal error occured

<br/>
<small>
{{$exception->getMessage()}}
<p>Contact the server admin to get help and explain what you were trying to do. Provide this error message to help fix the bug.</p>
</small>




@endsection
