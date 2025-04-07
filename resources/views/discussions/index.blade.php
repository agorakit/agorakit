@extends('app')

@section('content')
    @include('discussions.list', ['discussions' => $discussions])
@endsection
