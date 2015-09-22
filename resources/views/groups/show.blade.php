@extends('app')

@section('content')
    <h2>{{ $group->name }}</h2>

            <a href="{{ url('groups', $group->id) }}">{{ $group->name }}</a>




@endsection
