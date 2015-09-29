@extends('app')

@section('content')
    <h2>{{ $group->name }}</h2>

<p>
            {{ $group->body }}
</p>





@endsection
