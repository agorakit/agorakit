@extends('app')

@section('content')
    <h2>{{ $discussion->title }}</h2>

<p>
            {{ $discussion->body }}
</p>





@endsection
