@extends('group')

@section('content')
    @include('discussions.list', ['discussions' => $discussions])
@endsection
