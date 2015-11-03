@extends('app')

@section('content')

<h1>{{ $user->name }}</h1>
Inscription :  {{ $user->created_at->diffForHumans() }}


@endsection
